<?php

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Projects\Models\Event;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $project = $user->projects()->first();

        if (!$project) {
            return view('dashboard', [
                'project' => null,
                'totalEvents' => 0,
                'totalPurchaseValue' => 0,
                'chartData' => json_encode([]),
                'chartLabels' => json_encode([]),
                'plans' => \App\Models\SubscriptionPlan::orderBy('price', 'asc')->get(),
                'invoices' => $user->hasStripeId() ? $user->invoices() : collect()
            ]);
        }

        // 1. Total events
        $totalEvents = Event::where('project_id', $project->id)->count();

        // 2. Total Purchase Value (extracting from custom_data->value) SQL-agnostic fallback
        $totalPurchaseValue = 0;
        $purchases = Event::where('project_id', $project->id)->where('event_name', 'Purchase')->get();
        foreach ($purchases as $p) {
            if (isset($p->custom_data['value'])) {
                $totalPurchaseValue += (float)$p->custom_data['value'];
            }
        }

        // 3. Graph data: Events per day for last 7 days
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $eventsByDay = Event::where('project_id', $project->id)
            ->where('event_time', '>=', $sevenDaysAgo)
            ->select(DB::raw('DATE(event_time) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('count', 'date')->toArray();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartData[] = $eventsByDay[$dateStr] ?? 0;
        }

        $plans = \App\Models\SubscriptionPlan::orderBy('price', 'asc')->get();
        $invoices = $user->hasStripeId() ? $user->invoices() : collect();

        return view('dashboard', [
            'project' => $project,
            'totalEvents' => number_format($totalEvents),
            'totalPurchaseValue' => number_format($totalPurchaseValue, 2),
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'plans' => $plans,
            'invoices' => $invoices,
        ]);
    }
}
