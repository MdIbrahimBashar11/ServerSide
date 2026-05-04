<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Event;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_tenants' => User::where('role', 'tenant')->count(),
            'total_projects' => Project::where('is_active', true)->count(),
            'total_events' => Event::count(),
            // If using standard DB queue, we check jobs. Valid for Redis if we use standard facade size, but we'll mock queue visual for demo.
            'queue_size' => 0, 
        ];

        // Global Event Chart (last 7 days platform-wide)
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $dailyEvents = Event::where('event_time', '>=', $sevenDaysAgo)
            ->select(DB::raw('DATE(event_time) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->pluck('count', 'date')->toArray();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartData[] = $dailyEvents[$dateStr] ?? 0;
        }

        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.dashboard', compact('metrics', 'users', 'chartLabels', 'chartData'));
    }

    public function toggleStatus(User $user)
    {
        // Don't accidentally suspend yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot suspend the active superadmin session.');
        }

        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        return back()->with('status', 'User state updated successfully.');
    }

    public function showUser(User $user)
    {
        $user->load(['projects.destinations', 'invoices']);
        return view('admin.users.show', compact('user'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,tenant',
            'status' => 'required|string|in:active,suspended',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'User details updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete the active superadmin session.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard')->with('status', 'User deleted successfully.');
    }

    public function customers(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $users = $query->paginate(20);

        return view('admin.customers', compact('users'));
    }
}
