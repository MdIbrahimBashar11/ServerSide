<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Super Admin Control Center</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Manage global platform metrics, user accounts, and events tracker.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-900 text-sm font-bold shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Metrics Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Tenants</p>
                <p class="text-3xl font-black text-gray-900 leading-none">{{ number_format($metrics['total_tenants']) }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Active Projects</p>
                <p class="text-3xl font-black text-gray-900 leading-none">{{ number_format($metrics['total_projects']) }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Events Ingested</p>
                <p class="text-3xl font-black text-gray-900 leading-none">{{ number_format($metrics['total_events']) }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-[10px] font-bold text-rose-600 uppercase tracking-widest flex items-center gap-1.5 mb-2">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                        </span>
                        Live Queue Health
                    </p>
                    <p class="text-3xl font-black text-gray-900 leading-none">{{ $metrics['queue_size'] }}</p>
                </div>
                <p class="text-xs text-gray-400 font-medium mt-1">Pending Jobs</p>
            </div>
        </div>

        <!-- Global Trajectory Chart -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider mb-6">Platform-Wide Event Ingestion Tracker</h3>
            <div class="w-full h-72 bg-gray-50/50 rounded-xl border border-dashed border-gray-200 p-4 relative flex items-center justify-center">
                <canvas id="adminGlobalChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- User Management Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <header class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider">Platform Users & Tenants</h3>
            </header>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs uppercase bg-gray-50/80 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Tenant Name</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Role</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-right font-bold tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 flex flex-col justify-center">
                                <span class="font-bold text-gray-900">{{ $user->name }}</span>
                                <span class="text-xs text-gray-500 font-medium">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold rounded-md uppercase tracking-wider">Superadmin</span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-md uppercase tracking-wider">Tenant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">Suspended</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg transition">VIEW</a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg transition">EDIT</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->status === 'active')
                                            <button type="submit" class="text-xs font-bold text-rose-700 hover:text-rose-800 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-lg transition">SUSPEND</button>
                                        @else
                                            <button type="submit" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg transition">ACTIVATE</button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-700 hover:text-red-800 bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg transition">DELETE</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
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
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#10b981',
                            pointHoverBackgroundColor: '#fff',
                            pointRadius: 4,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#18181b',
                                titleFont: { size: 12, family: 'Inter', weight: 'bold' },
                                bodyFont: { size: 12, family: 'Inter' },
                                padding: 10,
                                boxPadding: 6
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { color: 'rgba(0, 0, 0, 0.03)' }, 
                                ticks: { color: '#71717a', font: { size: 10 } } 
                            },
                            x: { 
                                grid: { display: false }, 
                                ticks: { color: '#71717a', font: { size: 10 } } 
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
