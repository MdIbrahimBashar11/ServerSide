<?php

namespace App\Domains\Projects\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Event;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'custom_domain' => 'required|string|max:255|unique:projects,custom_domain',
            'plan_id' => 'nullable|exists:subscription_plans,id',
        ]);

        if ($request->user()->projects()->count() >= 5) {
            return back()->with('error', 'You have reached your tier limit for maximum projects.');
        }

        $project = $request->user()->projects()->create([
            'name' => $request->name,
            'custom_domain' => str_replace(['http://', 'https://', '/'], '', $request->custom_domain),
            'tracking_id' => 'trk_' . strtoupper(Str::random(12)),
            'is_active' => true,
        ]);

        if ($request->filled('plan_id')) {
            $plan = \App\Models\SubscriptionPlan::find($request->plan_id);
            if ($plan && $plan->price > 0) {
                return redirect()->route('billing.pay', $plan->id)->with('status', 'Please complete the payment for the selected plan.');
            }
        }

        return back()->with('status', 'Tracking infrastructure generated for ' . $project->custom_domain);
    }

    public function show(Project $project, Request $request)
    {
        if($project->user_id !== auth()->id()) abort(403);
        
        $totalEvents = Event::where('project_id', $project->id)->count();
        $accountTotalEvents = Event::where('user_id', auth()->id())->count();

        $successfulEvents = Event::where('project_id', $project->id)
            ->whereHas('deliveryLogs', function($q) {
                $q->where('status', 'success');
            })->count();

        $failedEvents = Event::where('project_id', $project->id)
            ->whereHas('deliveryLogs', function($q) {
                $q->where('status', 'failed');
            })->count();

        $pendingEvents = Event::where('project_id', $project->id)
            ->where('status', 'pending')
            ->count();

        $blockedEvents = Event::where('project_id', $project->id)
            ->where('source', 'blocked')
            ->count();

        $duplicatedEvents = Event::where('project_id', $project->id)
            ->where('source', 'duplicate')
            ->count();

        $latestEvent = Event::where('project_id', $project->id)->latest('event_time')->first();
        if (!$latestEvent) {
            $liveStatus = 'pending';
            $statusText = 'Awaiting First Event / Setup Pending';
        } elseif ($latestEvent->event_time->lt(now()->subDay())) {
            $liveStatus = 'error';
            $statusText = 'No Recent Data / Connection Idle';
        } else {
            $liveStatus = 'verified';
            $statusText = 'Active & Operational';
        }

        $query = Event::where('project_id', $project->id)->orderBy('event_time', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('event_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $events = $query->limit(10)->get();

        $chartData = collect();
        $maxChartValue = 10;
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            $dayTotal = Event::where('project_id', $project->id)
                ->whereDate('event_time', $date)
                ->count();
                
            $dayBlocked = Event::where('project_id', $project->id)
                ->whereDate('event_time', $date)
                ->where('source', 'blocked')
                ->count();
                
            $chartData->put($date, [
                'successful' => $dayTotal - $dayBlocked,
                'blocked' => $dayBlocked,
                'total' => $dayTotal,
                'day_name' => now()->subDays($i)->format('D')
            ]);
            
            if ($dayTotal > $maxChartValue) {
                $maxChartValue = $dayTotal;
            }
        }

        $performanceStats = Event::where('project_id', $project->id)
            ->where('event_time', '>=', now()->subDays(7)->startOfDay())
            ->selectRaw('event_name, count(*) as total')
            ->groupBy('event_name')
            ->orderByDesc('total')
            ->get();

        return view('projects.show', compact(
            'project', 'totalEvents', 'accountTotalEvents', 'successfulEvents', 'failedEvents', 
            'pendingEvents', 'blockedEvents', 'duplicatedEvents', 'events', 
            'liveStatus', 'statusText', 'chartData', 'maxChartValue', 'performanceStats'
        ));
    }

    public function export(Project $project, Request $request)
    {
        if($project->user_id !== auth()->id()) abort(403);

        $query = Event::where('project_id', $project->id)->orderBy('event_time', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('event_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=eventrix_export_' . date('Y-m-d') . '.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $callback = function() use($query) {
            $file = fopen('php://output', 'w');
            // Write Headers
            fputcsv($file, ['ID', 'Event Name', 'Platform', 'IP Address', 'User Agent', 'FBP', 'FBC', 'Event Time']);

            // Chunk DB retrieval to process infinitely without RAM crashing
            $query->chunk(1000, function($eventsChunk) use(&$file) {
                foreach ($eventsChunk as $e) {
                    $userData = $e->user_data ?? [];
                    fputcsv($file, [
                        $e->id,
                        $e->event_name,
                        $e->platform,
                        $userData['client_ip_address'] ?? 'Unknown',
                        $userData['client_user_agent'] ?? 'Unknown',
                        $userData['fbp'] ?? '',
                        $userData['fbc'] ?? '',
                        $e->event_time
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        $events = \App\Domains\Projects\Models\Event::where('project_id', $project->id)->orderBy('event_time', 'desc')->limit(10)->get();
        return view('projects.edit', compact('project', 'events'));
    }

    public function update(Request $request, Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        
        $request->validate([
            'name' => 'required|string',
            'website_url' => 'required|url',
            'custom_domain' => 'required|string|max:255|unique:projects,custom_domain,' . $project->id,
            'platform' => 'required|string'
        ]);

        $project->update([
            'name' => $request->name,
            'website_url' => $request->website_url,
            'custom_domain' => str_replace(['http://', 'https://', '/'], '', $request->custom_domain),
            'platform' => $request->platform
        ]);

        return back()->with('status', 'Basic Info saved successfully.');
    }

    public function setup(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        $events = \App\Domains\Projects\Models\Event::where('project_id', $project->id)->orderBy('event_time', 'desc')->limit(10)->get();
        return view('projects.setup', compact('project', 'events'));
    }

    public function verifyDomain(Project $project, \App\Domains\Projects\Services\DNSVerificationService $dnsService)
    {
        if($project->user_id !== auth()->id()) abort(403);

        $isVerified = $dnsService->verify($project);

        if ($isVerified) {
            return back()->with('status', 'Domain successfully connected and verified.');
        }

        return back()->with('error', 'Verification failed. Please ensure your CNAME record is correctly configured.');
    }
    public function updateDestinations(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $request->validate([
            'fb_pixel_id' => 'nullable|string|max:255',
            'fb_access_token' => 'nullable|string|max:1024',
            'tt_pixel_id' => 'nullable|string|max:255',
            'tt_access_token' => 'nullable|string|max:1024',
        ]);

        // Update Facebook CAPI
        if ($request->filled('fb_pixel_id') || $request->filled('fb_access_token')) {
            $project->destinations()->updateOrCreate(
                ['platform' => 'fb_capi'],
                [
                    'dataset_id' => $request->fb_pixel_id,
                    'access_token' => $request->fb_access_token,
                    'is_active' => true
                ]
            );
        }

        // Update TikTok API
        if ($request->filled('tt_pixel_id') || $request->filled('tt_access_token')) {
            $project->destinations()->updateOrCreate(
                ['platform' => 'tiktok'],
                [
                    'dataset_id' => $request->tt_pixel_id,
                    'access_token' => $request->tt_access_token,
                    'is_active' => true
                ]
            );
        }

        return back()->with('status', 'API Destinations updated successfully.');
    }

    public function events(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        $events = Event::where('project_id', $project->id)->orderBy('event_time', 'desc')->paginate(50);
        return view('projects.events', compact('project', 'events'));
    }

    public function deliveryLogs(Project $project, Event $event)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $logs = \App\Domains\Projects\Models\EventDeliveryLog::where('event_id', $event->id)
            ->with(['event', 'destination'])
            ->latest()
            ->limit(50)
            ->get();

        return response()->json($logs);
    }

    public function eventsJson(Project $project)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $events = Event::where('project_id', $project->id)
            ->orderBy('event_time', 'desc')
            ->limit(10)
            ->get();

        return response()->json($events);
    }

    public function downloadPlugin(Project $project)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $trackingUrl = $project->domain_status === 'verified'
            ? "https://{$project->custom_domain}/api/track-event"
            : config('app.url') . "/api/track-event";
        $trackingId = $project->tracking_id;
        $faviconUrl = config('app.url') . "/favicon.ico";

        // Template folder path
        $templatePath = base_path('servertrack-pixel_v_1_2_1');

        if (!is_dir($templatePath)) {
            return back()->with('error', 'WordPress plugin template source missing. Please contact engineering support.');
        }

        $zipFile = tempnam(sys_get_temp_dir(), 'eventrix') . '.zip';
        $zip = new \ZipArchive();

        if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($templatePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($templatePath) + 1);
                
                // Normalize backslashes to forward slashes for ZIP paths
                $zipPath = 'eventrix/' . str_replace('\\', '/', $relativePath);

                if ($file->isDir()) {
                    $zip->addEmptyDir($zipPath);
                } else {
                    $filename = $file->getFilename();
                    
                    // Pre-fill tracking ID, custom tracking URL, and favicon dynamically
                    if ($filename === 'eventrix-pixel.php') {
                        $content = file_get_contents($filePath);
                        $content = str_replace(
                            array('%%TRACKING_ID%%', '%%TRACKING_URL%%', '%%FAVICON_URL%%'),
                            array($trackingId, $trackingUrl, $faviconUrl),
                            $content
                        );
                        $zip->addFromString($zipPath, $content);
                    } else {
                        $zip->addFile($filePath, $zipPath);
                    }
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Unable to generate plugin zip package.');
        }

        return response()->download($zipFile, 'eventrix.zip')->deleteFileAfterSend(true);
    }
}
