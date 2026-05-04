@once
<!-- Live Debugger Modal -->
<x-modal name="event-debugger" maxWidth="5xl">
    <div class="p-0 bg-white h-[90vh] md:h-[85vh] flex flex-col overflow-hidden" 
         x-data="{ 
            selectedEvent: null, 
            events: {{ json_encode($events) }},
            logs: [], 
            loading: false,
            pollInterval: null,
            init() {
                this.pollEvents();
                this.pollInterval = setInterval(() => this.pollEvents(), 3000);
            },
            destroy() {
                if (this.pollInterval) clearInterval(this.pollInterval);
            },
            pollEvents() {
                fetch('{{ route('projects.events.json', $project->id) }}')
                    .then(r => r.json())
                    .then(data => {
                        this.events = data;
                    })
                    .catch(e => console.error(e));
            },
            fetchLogs(eventData) {
                if (this.selectedEvent && this.selectedEvent.id === eventData.id) {
                    this.selectedEvent = null;
                    this.logs = [];
                    return;
                }
                
                this.selectedEvent = eventData;
                this.loading = true;
                this.logs = [];
                
                fetch('/projects/{{ $project->id }}/events/' + eventData.id + '/logs')
                    .then(r => r.json())
                    .then(data => { 
                        this.logs = data; 
                        this.loading = false; 
                    })
                    .catch(e => {
                        this.loading = false;
                    });
                
                // On mobile devices, scroll to the inspector area
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        const inspector = document.getElementById('details-inspector');
                        if(inspector) inspector.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
            }
         }"
         @close.window="if (pollInterval) clearInterval(pollInterval)">
        
        <!-- Modal Header -->
        <div class="p-4 md:p-6 border-b border-gray-100 flex items-center justify-between bg-white z-30 shadow-sm">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-600 rounded-lg md:rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 leading-none">Debugger Tools</h2>
                    <p class="text-[8px] md:text-[10px] font-bold text-gray-400 mt-1.5 md:mt-2 uppercase tracking-widest">Protocol Handshake Log — Last 10 Nodes</p>
                </div>
            </div>
            
            <button @click="$dispatch('close')" class="p-2 text-gray-400 hover:text-gray-900 transition bg-gray-50 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Modal Content (Split Pane) -->
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden bg-gray-50/30">
            
            <!-- Left Pane: Event List (Sidebar) -->
            <div class="w-full md:w-80 lg:w-96 flex-shrink-0 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col bg-white max-h-[40vh] md:max-h-full shadow-sm z-10">
                <div class="p-4 md:p-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Events Pool</span>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100" x-text="events.length + ' Live'"></span>
                </div>
                
                <div class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50/10">
                    <template x-for="event in events" :key="event.id">
                        <button @click="fetchLogs(event)" 
                                class="w-full p-4 md:p-6 text-left border-b border-gray-100 hover:bg-white transition-all group relative overflow-hidden"
                                :class="selectedEvent && selectedEvent.id == event.id ? 'bg-white shadow-md z-10' : 'opacity-70 hover:opacity-100'">
                            
                            <!-- Active Status Line -->
                            <div x-show="selectedEvent && selectedEvent.id == event.id" 
                                 class="absolute inset-y-0 left-0 w-1.5 bg-emerald-600 shadow-[2px_0_12px_rgba(16,185,129,0.5)]"></div>
                            
                            <div class="flex justify-between items-start mb-2 md:mb-3">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest" x-text="new Date(event.event_time).toLocaleTimeString()"></span>
                                <span class="text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-tighter" 
                                      :class="event.platform === 'fb_capi' ? 'bg-blue-600 text-white' : 'bg-gray-900 text-white'"
                                      x-text="event.platform"></span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 truncate uppercase tracking-tight group-hover:text-emerald-600 transition-colors"
                                :class="selectedEvent && selectedEvent.id == event.id ? 'text-emerald-700' : ''"
                                x-text="event.event_name">
                            </h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-1.5 md:mt-2 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span x-text="event.user_data && event.user_data.client_ip_address ? event.user_data.client_ip_address : 'N/A'"></span>
                            </p>
                        </button>
                    </template>

                    <div x-show="events.length === 0" class="py-12 md:py-24 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">Awaiting node capture...</p>
                    </div>
                </div>
            </div>

            <!-- Right Pane: Details Inspector (Main Area) -->
            <div id="details-inspector" class="flex-1 flex flex-col bg-white overflow-hidden relative">
                
                <!-- Inspector Body -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
                    
                    <!-- Empty State -->
                    <div x-show="!selectedEvent" class="h-full flex flex-col items-center justify-center p-8 md:p-12 text-center opacity-40">
                         <div class="w-16 h-16 md:w-24 md:h-24 bg-emerald-50 rounded-2xl md:rounded-[2rem] flex items-center justify-center text-emerald-600 mb-8 border border-emerald-100 animate-pulse">
                            <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                         </div>
                         <h4 class="text-base font-bold text-gray-900 uppercase tracking-widest mb-2">Debugger Standby</h4>
                         <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Select a protocol node from the pool to audit transmission payload</p>
                    </div>

                    <!-- Selected Event Content -->
                    <div x-show="selectedEvent" x-cloak class="flex flex-col h-full bg-white">
                        
                        <!-- Header for Details -->
                        <div class="p-6 md:p-10 border-b border-gray-100 bg-white sticky top-0 z-10 shadow-sm">
                            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                                <div>
                                    <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1">Inbound Transmission</h3>
                                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 uppercase tracking-tighter" x-text="selectedEvent.event_name"></h1>
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="'Ref: ' + selectedEvent.id"></span>
                                        <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="selectedEvent.event_time"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button @click="navigator.clipboard.writeText(JSON.stringify(selectedEvent, null, 2))" class="px-5 py-2.5 bg-gray-900 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-black transition shadow-lg shadow-gray-900/10 active:scale-95">
                                        Copy Packet
                                    </button>
                                    <button @click="selectedEvent = null" class="md:hidden px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-200 transition">
                                        Back to Pool
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 md:p-10 space-y-10 lg:space-y-12">
                            
                            <!-- Inbound Raw Data -->
                            <div class="space-y-5">
                                <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-3">
                                    <span class="w-4 h-[1px] bg-gray-200"></span>
                                    Source JSON Payload
                                </h5>
                                <div class="bg-gray-950 rounded-2xl p-6 md:p-8 overflow-auto max-h-[400px] shadow-2xl border border-white/5">
                                    <pre class="text-emerald-400 font-mono text-[10px] md:text-[12px] leading-relaxed"><code x-text="JSON.stringify(selectedEvent, null, 2)"></code></pre>
                                </div>
                            </div>

                            <!-- Destination Handshakes -->
                            <div class="space-y-6">
                                <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-3">
                                    <span class="w-4 h-[1px] bg-gray-200"></span>
                                    API Destination Routing
                                </h5>
                                
                                <div x-show="loading" class="py-20 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-emerald-600 border-t-transparent mb-6"></div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest animate-pulse">Establishing Handshakes...</p>
                                </div>

                                <div x-show="!loading" class="space-y-6">
                                    <template x-for="log in logs" :key="log.id">
                                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                                            <div class="px-6 py-5 bg-gray-50 flex flex-wrap justify-between items-center gap-4 border-b border-gray-100">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                                                        <span class="text-xs font-black text-gray-900" x-text="log.destination.platform.substring(0, 2).toUpperCase()"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block" x-text="log.destination.platform"></span>
                                                        <span class="text-[9px] font-bold text-gray-400 uppercase" x-text="'HTTP ' + log.response_code"></span>
                                                    </div>
                                                </div>
                                                <span class="text-[9px] font-bold uppercase tracking-widest px-5 py-2 rounded-full border shadow-sm transition-all" 
                                                      :class="log.status === 'success' ? 'bg-emerald-500 text-white border-emerald-400' : 'bg-red-500 text-white border-red-400'"
                                                      x-text="log.status === 'success' ? 'Handshake Success' : 'Transmission Failed'"></span>
                                            </div>
                                            <div class="p-6 md:p-8">
                                                <div class="bg-gray-900 p-6 md:p-8 rounded-xl overflow-auto max-h-64 font-mono text-[10px] md:text-[12px] text-emerald-400 leading-relaxed shadow-inner border border-white/5">
                                                    <pre x-text="log.response_body"></pre>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <div x-show="logs.length === 0" class="py-20 bg-white border-2 border-dashed border-gray-100 rounded-[2rem] text-center">
                                         <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                         </div>
                                         <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No outbound handshake detected for this packet</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-modal>

<!-- Scripts & Styles for Debugger -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if page should open modal immediately (if filtered)
        @if(request('start_date') || request('platform'))
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'event-debugger' }));
            }, 100);
        @endif
    });
</script>
<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; border: 2px solid #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endonce
