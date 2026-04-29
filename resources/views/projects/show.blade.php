<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <!-- Tabs / Subnav -->
        @include('projects._nav')

        @if($project->domain_status === 'connection_lost')
            <div class="p-8 bg-red-600 border border-red-500 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6 text-white shadow-2xl shadow-red-600/20">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center animate-pulse">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">Critical Connectivity Alert: DNS Fault Detected</h2>
                        <p class="text-red-100 text-sm mt-1 font-bold">First-party tracking is currently offline for <span class="underline">{{ $project->custom_domain }}</span>. Handshake verification failed.</p>
                    </div>
                </div>
                <a href="{{ route('projects.setup', $project->id) }}" class="px-8 py-4 bg-white text-red-600 rounded-xl font-bold text-sm uppercase tracking-widest shadow-xl transition hover:bg-gray-50 active:scale-95">Resolve Instantly</a>
            </div>
        @endif

        @if(session('status'))
            <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-4 text-emerald-900 text-sm font-bold shadow-sm">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Overview Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Project Identification -->
            <div class="lg:col-span-2 bg-white p-10 rounded-xl border border-gray-200 shadow-sm space-y-12">
                <div>
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-16 h-16 bg-gray-900 rounded-xl flex items-center justify-center text-emerald-400 shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $project->name }}</h1>
                            <p class="text-base text-gray-500 mt-1 font-bold">{{ $project->website_url }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Primary Workflow</p>
                            <p class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{ $project->platform ?? 'Native Integration' }}</p>
                        </div>
                        <div class="p-6 rounded-xl border {{ $project->domain_status === 'verified' ? 'bg-emerald-50/50 border-emerald-100' : ($project->domain_status === 'pending' ? 'bg-amber-50/50 border-amber-100' : 'bg-red-50 border-red-100') }}">
                            <p class="text-[10px] font-bold {{ $project->domain_status === 'verified' ? 'text-emerald-600' : ($project->domain_status === 'pending' ? 'text-amber-600' : 'text-red-600') }} uppercase tracking-widest mb-2">Live Status</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 {{ $project->domain_status === 'verified' ? 'bg-emerald-500' : ($project->domain_status === 'pending' ? 'bg-amber-500 animate-pulse' : 'bg-red-500') }} rounded-full shadow-sm"></span>
                                <p class="text-sm font-bold {{ $project->domain_status === 'verified' ? 'text-emerald-800' : ($project->domain_status === 'pending' ? 'text-amber-800' : 'text-red-800') }}">
                                    @if($project->domain_status === 'verified')
                                        Operational & Syncing
                                    @elseif($project->domain_status === 'pending')
                                        Verification Pending
                                    @else
                                        Connection Lost / Failed
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ showKey: false }">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Project Authentication Key</label>
                    <div class="flex items-center gap-4 bg-gray-900 p-5 rounded-xl border border-white/5 shadow-inner">
                        <code class="flex-1 font-mono text-sm text-emerald-400 truncate tracking-widest" x-text="showKey ? '{{ $project->tracking_id }}' : '•••••••••••••••••••••••••••••••••'"></code>
                        <div class="flex items-center gap-4 border-l border-white/10 pl-5">
                            <button @click="showKey = !showKey" class="text-gray-400 hover:text-white transition">
                                <svg x-show="!showKey" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showKey" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04a11.959 11.959 0 012.316-2.507m2.316-2.316A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-4.225-4.225l-4.703-4.703m0 0L9 9m4.775 4.775L15 15M9 9l-4.725-4.725M12 12L9 9"></path></svg>
                            </button>
                            <button onclick="navigator.clipboard.writeText('{{ $project->tracking_id }}')" class="text-gray-400 hover:text-emerald-400 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Analytics -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                    @php
                        $limit = Auth::user()->subscriptionPlan->event_limit ?? 10000;
                        $percent = min(($totalEvents / $limit) * 100, 100);
                    @endphp
                    
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-10 border-l-4 border-gray-900 pl-4">Account Throughput</h3>
                    
                    <div class="mb-10">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Efficiency</span>
                            <span class="text-3xl font-bold text-gray-900 tracking-tighter">{{ number_format($percent, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3 mb-4">
                            <div class="bg-gray-900 h-full rounded-full transition-all duration-[1500ms]" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest text-center">{{ number_format($totalEvents) }} / {{ number_format($limit) }} events tracked</p>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-gray-50">
                        <div class="flex justify-between text-sm font-bold">
                            <span class="text-gray-400 uppercase tracking-widest text-[10px]">Project Data</span>
                            <span class="text-gray-900">{{ number_format($totalEvents) }} ev</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold">
                            <span class="text-gray-400 uppercase tracking-widest text-[10px]">Headroom</span>
                            <span class="text-emerald-600">+{{ number_format(max(0, $limit - $totalEvents)) }}</span>
                        </div>
                    </div>
                </div>

                <!-- NEW: Infrastructure Health Widget -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8 border-l-4 border-emerald-500 pl-4">Edge Infrastructure Health</h3>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Connectivity</span>
                            @if($project->domain_status === 'verified')
                                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Strong
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-red-600 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Action Required
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Global Latency</span>
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">34ms (Average)</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SSL Status</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $project->ssl_status === 'active' ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ strtoupper($project->ssl_status ?? 'NOT FOUND') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-50">
                         <div class="flex items-center gap-3">
                             <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                             <p class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Last Checked: {{ $project->last_check_at ? $project->last_check_at->diffForHumans() : 'Never' }}</p>
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-data="{}" class="bg-emerald-600 rounded-xl p-10 flex flex-col md:flex-row items-center justify-between gap-8 shadow-xl shadow-emerald-600/10 border border-emerald-500 transition-all hover:scale-[1.01]">
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-bold text-white tracking-tight">Advanced Protocol Debugging</h2>
                <p class="text-emerald-50 mt-1 font-bold text-sm">Access the real-time event inspector and delivery handshake logs.</p>
            </div>
            <button @click="$dispatch('open-modal', 'event-debugger')" class="px-10 py-5 bg-white text-emerald-900 rounded-xl font-bold text-sm shadow-2xl transition hover:bg-gray-50 active:scale-95 uppercase tracking-widest">
                Open Debugger Tool
            </button>
        </div>

    </div>

    <!-- Live Debugger Modal -->
    <x-modal name="event-debugger" maxWidth="7xl">
        <div class="p-0 bg-white h-[85vh] flex flex-col overflow-hidden" 
             x-data="{ 
                selectedEvent: null, 
                logs: [], 
                loading: false,
                fetchLogs(eventData) {
                    this.selectedEvent = eventData;
                    this.loading = true;
                    fetch('/projects/{{ $project->id }}/events/' + eventData.id + '/logs')
                        .then(r => r.json())
                        .then(data => { 
                            this.logs = data; 
                            this.loading = false; 
                        });
                }
             }">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white z-20">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 leading-none">Debugger Tools</h2>
                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Live event tracking — Only shows events captured by node</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-100">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest">Active</span>
                    </div>
                    <button @click="$dispatch('close')" class="p-2 text-gray-400 hover:text-gray-900 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content (Split Pane) -->
            <div class="flex-1 flex overflow-hidden">
                
                <!-- Left Pane: Event List -->
                <div class="w-1/3 border-r border-gray-100 flex flex-col bg-gray-50/50">
                    <div class="p-6 border-b border-gray-100 bg-white">
                        <div class="flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <span>Events Pool</span>
                            <span>{{ $events->count() }} detected</span>
                        </div>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($events as $event)
                            <button @click="fetchLogs({{ json_encode($event) }})" 
                                    class="w-full p-6 text-left border-b border-gray-100 hover:bg-white transition-all group relative overflow-hidden"
                                    :class="selectedEvent && selectedEvent.id == {{ $event->id }} ? 'bg-white shadow-sm z-10' : ''">
                                <div x-show="selectedEvent && selectedEvent.id == {{ $event->id }}" class="absolute inset-y-0 left-0 w-1 bg-emerald-600"></div>
                                
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $event->event_time->format('H:i:s') }}</span>
                                    <span class="text-[8px] font-bold px-2 py-0.5 rounded-full {{ $event->platform === 'fb_capi' ? 'bg-blue-50 text-blue-600' : 'bg-gray-200 text-gray-700' }} uppercase tracking-tighter">{{ $event->platform }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 truncate uppercase tracking-tight group-hover:text-emerald-600 transition-colors">{{ $event->event_name }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold mt-2 uppercase tracking-widest">{{ $event->user_data['client_ip_address'] ?? 'N/A' }}</p>
                            </button>
                        @empty
                            <div class="py-24 text-center">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest italic px-8">Waiting for events to hit core infrastructure...</p>
                            </div>
                        @endforelse
                    </div>

                    @if($events->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-white px-6">
                            {{ $events->links('pagination::simple-tailwind') }}
                        </div>
                    @endif
                </div>

                <!-- Right Pane: Details Inspector -->
                <div class="flex-1 flex flex-col bg-white">
                    
                    <!-- Detail Header -->
                    <div class="p-8 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Event Details</h3>
                        <p class="text-xs text-gray-500 font-bold" x-show="!selectedEvent">Select an event from the pool to audit its transmission logs.</p>
                        <p class="text-xl font-bold text-gray-900" x-show="selectedEvent" x-text="selectedEvent.event_name + ' Payload'"></p>
                    </div>

                    <!-- Inspector Body -->
                    <div class="flex-1 overflow-y-auto bg-white p-0">
                        
                        <!-- Empty State -->
                        <div x-show="!selectedEvent" class="h-full flex flex-col items-center justify-center p-12 text-center opacity-30 grayscale grayscale-0">
                             <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-6">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                             </div>
                             <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Select node event to begin audit</p>
                        </div>

                        <!-- Data Viewer -->
                        <div x-show="selectedEvent" class="p-8 space-y-10" x-cloak>
                            
                            <!-- Inbound Payload -->
                            <div class="space-y-4">
                                <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Inbound Tracking Data</h5>
                                <div class="bg-gray-950 rounded-xl p-8 overflow-auto max-h-[300px] shadow-2xl relative group">
                                    <button @click="navigator.clipboard.writeText(JSON.stringify(selectedEvent, null, 2))" class="absolute top-4 right-4 text-gray-500 hover:text-white transition opacity-0 group-hover:opacity-100 uppercase text-[9px] font-bold">Copy JSON</button>
                                    <pre class="text-emerald-400 font-mono text-[11px] leading-relaxed"><code x-text="JSON.stringify(selectedEvent, null, 2)"></code></pre>
                                </div>
                            </div>

                            <!-- Platform Responses -->
                            <div class="space-y-6">
                                <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sync Transmission Logs</h5>
                                
                                <div x-show="loading" class="py-12 text-center">
                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-emerald-600 border-t-transparent mb-4"></div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Handshaking with destinations...</p>
                                </div>

                                <div x-show="!loading" class="space-y-4">
                                    <template x-for="log in logs" :key="log.id">
                                        <div class="border border-gray-100 rounded-xl overflow-hidden group">
                                            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center border-b border-gray-100">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest" x-text="log.destination.platform"></span>
                                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-white border border-gray-100 text-gray-400" x-text="log.response_code"></span>
                                                </div>
                                                <span class="text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full" 
                                                      :class="log.status === 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100'"
                                                      x-text="log.status === 'success' ? 'Synchronized' : 'Rejected'"></span>
                                            </div>
                                            <div class="p-6 bg-gray-50/20">
                                                <div class="bg-gray-900 p-6 rounded-xl overflow-auto max-h-40 font-mono text-[10px] text-emerald-400 leading-relaxed shadow-inner">
                                                    <pre x-text="log.response_body"></pre>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="logs.length === 0" class="py-12 border-2 border-dashed border-gray-100 rounded-xl text-center">
                                         <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No outbound logs detected for this cycle</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </x-modal>

    <!-- Scripts -->
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
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</x-app-layout>
