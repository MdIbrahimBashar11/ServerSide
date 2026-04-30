<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <!-- Navigation Tabs -->
        @include('projects._nav')

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 md:p-10 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Event Ledger</h1>
                    <p class="text-sm text-gray-500 mt-1 font-bold">Comprehensive log of all inbound node transmissions for this project.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('projects.export', $project->id) }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-black transition shadow-lg active:scale-95">
                        Export Dataset
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Timestamp</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Event</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Origin Platform</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Client IP</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Status</th>
                           
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($events as $event)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-xs font-bold text-gray-900">{{ $event->event_time->format('Y-m-d') }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold block mt-0.5">{{ $event->event_time->format('H:i:s') }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{ $event->event_name }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold block mt-0.5 truncate max-w-[200px] uppercase tracking-tighter">{{ $event->id }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $event->platform === 'fb_capi' ? 'bg-blue-600' : 'bg-gray-900' }}"></div>
                                        <span class="text-xs font-bold text-gray-700 uppercase">{{ $event->platform }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-xs font-mono font-bold text-gray-600">{{ $event->user_data['client_ip_address'] ?? '0.0.0.0' }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-widest">
                                        Captured
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No events have been captured yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="px-6 py-6 border-t border-gray-50 bg-gray-50/30">
                    {{ $events->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Script to handle opening debugger from table -->
    <script>
        window.addEventListener('select-event', event => {
            // Find the debugger Alpine instance and trigger fetch
            // This assumes the modal is already in the DOM via _nav
            // We use a custom event to bridge the gap
            const debuggerInstance = document.querySelector('[x-data*="selectedEvent"]');
            if (debuggerInstance && debuggerInstance.__x) {
                debuggerInstance.__x.$data.fetchLogs(event.detail);
            }
        });
    </script>
</x-app-layout>
