<?php

namespace App\Domains\EventIngestion\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Event;
use App\Domains\EventIngestion\Jobs\ProcessEventJob;
use App\Domains\EventIngestion\Events\EventIngested;

class IngestionController extends Controller
{
    public function collect(Request $request)
    {
        // 1. Resolve Project: Priority to Middleware (Domain-based) -> Header -> Query
        $project = $request->resolved_project;

        if (!$project) {
            $trackingId = $request->header('X-Tracking-Id') ?? $request->query('tracking_id') ?? $request->input('tracking_id');
            $project = $trackingId ? Project::where('tracking_id', $trackingId)->first() : null;
        }

        // Edge Security: Dynamic Redis Rate Limiting
        if ($project) {
            $tenant = \App\Models\User::find($project->user_id);
            if ($tenant) {
                // Tier-based dynamic throttling: Free getting 1k req/min, Premium getting 10k req/min
                $reqPerMin = $tenant->event_limit >= 100000 ? 10000 : 1000;
                $throttleKey = 'ingest_throttle_' . $project->tracking_id;
                
                if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, $reqPerMin)) {
                    \Log::warning("Edge node rate limited connection for Project: {$project->id}");
                    return response()->json(['error' => 'Too Many Requests. Node throttled.'], 429);
                }
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);

                // Target Pausing Enforcement
                $usage = Event::whereIn('project_id', $tenant->projects()->pluck('id'))->count();
                if ($usage >= $tenant->event_limit) {
                    return response()->json(['error' => 'Monthly billing limit exceeded. Data ingestion paused.'], 402);
                }
            }
        }
        // 2. Validate request data
        $validated = $request->validate([
            'user_id' => 'nullable|string',
            'event_name' => 'required|string|in:PageView,ViewContent,Search,AddToCart,AddToWishlist,InitiateCheckout,AddPaymentInfo,Purchase,Lead',
            'event_id' => 'required|string',
            'timestamp' => 'nullable|integer',
            'user_data' => 'nullable|array',
            'custom_data' => 'nullable|array',
        ]);

        // 3. Deduplication check via event_id
        if (Event::where('event_id', $validated['event_id'])->exists()) {
            return response()->json(['status' => 'duplicate', 'message' => 'Event already exists'], 200);
        }

        // Capture all user data and inject server-side info
        $userData = $request->input('user_data', []);
        if (!is_array($userData)) $userData = [];
        
        // Robust IP detection
        $ip = $request->header('X-Forwarded-For') 
              ?? $request->header('X-Real-IP') 
              ?? $request->ip();
        
        $userData['client_ip_address'] = $userData['client_ip_address'] ?? $ip;
        $userData['client_user_agent'] = $userData['client_user_agent'] ?? $request->userAgent();
        $userData['page_url'] = $userData['page_url'] ?? $request->header('referer');

        // 4. Store raw event in database
        $event = Event::create([
            'project_id' => $project?->id,
            'event_id' => $validated['event_id'],
            'event_name' => $validated['event_name'],
            'user_id' => $validated['user_id'] ?? null,
            'user_data' => $userData,
            'custom_data' => $validated['custom_data'] ?? [],
            'event_time' => isset($validated['timestamp']) ? date('Y-m-d H:i:s', $validated['timestamp']) : now(),
        ]);

        // 5. Broadcast to live dashboard channels
        EventIngested::dispatch($event);

        // 6. Push event to Redis queue for async processing
        ProcessEventJob::dispatch($event);

        // 5. Fast 200 Return
        return response()->json(['status' => 'success', 'event_id' => $event->id]);
    }
}
