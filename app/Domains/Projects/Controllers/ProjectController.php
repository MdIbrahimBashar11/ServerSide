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
            'custom_domain' => 'required|string|max:255|unique:projects,custom_domain'
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

        return back()->with('status', 'Tracking infrastructure generated for ' . $project->custom_domain);
    }

    public function show(Project $project, Request $request)
    {
        if($project->user_id !== auth()->id()) abort(403);
        
        $totalEvents = Event::where('project_id', $project->id)->count();

        $query = Event::where('project_id', $project->id)->orderBy('event_time', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('event_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $events = $query->paginate(30)->withQueryString();

        return view('projects.show', compact('project', 'totalEvents', 'events'));
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
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        
        $request->validate([
            'name' => 'required|string',
            'website_url' => 'required|url',
            'platform' => 'required|string'
        ]);

        $project->update([
            'name' => $request->name,
            'website_url' => $request->website_url,
            'platform' => $request->platform
        ]);

        return back()->with('status', 'Basic Info saved successfully.');
    }

    public function setup(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        return view('projects.setup', compact('project'));
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
}
