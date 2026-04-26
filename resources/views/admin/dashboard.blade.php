<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-zinc-100 leading-tight">
            {{ __('Super Admin Control Center') }}
        </h2>
    </x-slot>

    <!-- Deep Dark Theme Override -->
    <style>
        body { background-color: #09090b !important; }
        .admin-card {
            background: rgba(24, 24, 27, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.05), 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <div class="admin-card rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <p class="text-sm font-medium text-zinc-400 uppercase tracking-widest mb-1">Total Tenants</p>
                    <p class="text-4xl font-extrabold text-white">{{ number_format($metrics['total_tenants']) }}</p>
                </div>

                <div class="admin-card rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <p class="text-sm font-medium text-zinc-400 uppercase tracking-widest mb-1">Active Projects</p>
                    <p class="text-4xl font-extrabold text-white">{{ number_format($metrics['total_projects']) }}</p>
                </div>

                <div class="admin-card rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <p class="text-sm font-medium text-zinc-400 uppercase tracking-widest mb-1">Total Events Ingested</p>
                    <p class="text-4xl font-extrabold text-white">{{ number_format($metrics['total_events']) }}</p>
                </div>

                <!-- Redis Queue Monitor Widget -->
                <div class="admin-card rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <p class="text-xs font-semibold text-rose-500 uppercase tracking-widest flex items-center gap-2">
                             <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                            Live Queue Health
                        </p>
                        <p class="text-3xl font-extrabold text-white mt-2">{{ $metrics['queue_size'] }}</p>
                    </div>
                    <p class="text-xs text-zinc-500 mt-2">Pending Jobs in Redis Array</p>
                </div>
            </div>

            <!-- Global Trajectory Chart -->
            <div class="admin-card rounded-3xl p-8 relative">
                <h3 class="text-lg font-semibold text-white mb-6">Platform-Wide Event Ingestion Tracker</h3>
                <div class="w-full h-72">
                    <canvas id="adminGlobalChart"></canvas>
                </div>
            </div>

            <!-- User Management Data Table -->
            <div class="admin-card rounded-3xl overflow-hidden">
                <header class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white">Platform Users & Tenants</h3>
                </header>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-zinc-300">
                        <thead class="text-xs uppercase bg-zinc-900 text-zinc-500 border-b border-zinc-800">
                            <tr>
                                <th class="px-6 py-4">Tenant Name</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Joined</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            @foreach($users as $user)
                            <tr class="hover:bg-zinc-800/30 transition">
                                <td class="px-6 py-4 flex flex-col">
                                    <span class="font-medium text-zinc-100">{{ $user->name }}</span>
                                    <span class="text-xs text-zinc-500">{{ $user->email }}</span>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    @if($user->role === 'admin')
                                        <span class="px-2 py-1 bg-rose-500/20 text-rose-400 rounded-md">SUPERADMIN</span>
                                    @else
                                        <span class="text-indigo-400">TENANT</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">Suspended</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->status === 'active')
                                                <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-400 bg-rose-500/10 tracking-widest px-3 py-1.5 rounded-lg border border-rose-500/20 transition">SUSPEND</button>
                                            @else
                                                <button type="submit" class="text-xs font-semibold text-emerald-500 hover:text-emerald-400 bg-emerald-500/10 tracking-widest px-3 py-1.5 rounded-lg border border-emerald-500/20 transition">ACTIVATE</button>
                                            @endif
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-zinc-800">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Logic -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('adminGlobalChart');
            if(ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Global Events Ingested',
                            data: {!! json_encode($chartData) !!},
                            borderColor: '#10b981', // Emerald-500
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointHoverBackgroundColor: '#fff',
                            pointRadius: 0,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFont: { size: 14, family: 'Inter' },
                                bodyFont: { size: 14, family: 'Inter' },
                                padding: 12,
                                boxPadding: 6
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { color: 'rgba(255,255,255,0.05)', borderDash: [4, 4] }, 
                                ticks: { color: '#71717a' } 
                            },
                            x: { 
                                grid: { display: false }, 
                                ticks: { color: '#71717a' } 
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                    }
                });
            }
        });
    </script>
</x-app-layout>
