<?php

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Projects\Models\Event;
use App\Domains\Projects\Models\EventDeliveryLog;

class IntegrationController extends Controller
{
    public function logs(Request $request)
    {
        $project = $request->user()->projects()->first();
        if (!$project) return redirect()->route('dashboard');

        // Basic event stream
        $events = Event::where('project_id', $project->id)
            ->orderBy('event_time', 'desc')
            ->paginate(50);

        return view('events.logs', compact('events'));
    }

    public function settings(Request $request)
    {
        $project = $request->user()->projects()->first();
        if (!$project) return redirect()->route('dashboard');

        $destinations = $project->destinations;

        return view('events.settings', compact('destinations', 'project'));
    }
}
