<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        
        <div class="border-b border-gray-100 pb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-[0.9] font-outfit uppercase italic tracking-tighter mb-4">EVENT <span class="text-emerald-500">PROPAGATION</span></h1>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em] flex items-center gap-3 italic">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Quantum Pulse Stream & Real-time Signal Processing
            </p>
        </div>

        <div class="bg-white rounded-[3.5rem] border border-gray-100 shadow-sm overflow-hidden relative group">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 border-b border-gray-50 text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] italic">
                        <tr>
                            <th scope="col" class="px-10 py-6 leading-none">Transmission_Time</th>
                            <th scope="col" class="px-10 py-6 leading-none">Signal_Event</th>
                            <th scope="col" class="px-10 py-6 leading-none">Vector_ID</th>
                            <th scope="col" class="px-10 py-6 leading-none">Origin_IP</th>
                            <th scope="col" class="px-10 py-6 text-right leading-none">Value_Metric</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($events as $event)
                            <tr class="group/row hover:bg-slate-50 transition-all">
                                <td class="px-10 py-8 whitespace-nowrap text-[11px] font-bold text-slate-400 uppercase tracking-tighter">
                                    {{ $event->event_time->format('M d, Y') }} <span class="opacity-30 ml-2">{{ $event->event_time->format('h:i:s A') }}</span>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                        <span class="text-sm font-black text-gray-900 font-outfit uppercase italic tracking-tight">{{ $event->event_name }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 font-mono text-[10px] text-slate-400 tracking-tight uppercase opacity-50">{{ substr($event->event_id, 0, 16) }}</td>
                                <td class="px-10 py-8">
                                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200">
                                        {{ $event->user_data['client_ip_address'] ?? '0.0.0.0' }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 text-right font-black text-slate-900 font-outfit uppercase italic">
                                    @if(isset($event->custom_data['value']))
                                        {{ $event->custom_data['currency'] ?? '৳' }}{{ number_format($event->custom_data['value'], 0) }}
                                    @else
                                        <span class="text-slate-300">--</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-24 text-center">
                                    <div class="w-24 h-24 bg-slate-100 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-400 uppercase italic tracking-widest font-outfit">No Signals Detected</h3>
                                    <p class="text-[9px] text-slate-300 font-black uppercase tracking-[0.3em] mt-2 italic opacity-60">Initialize your integration clusters to begin processing traffic.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-50">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
