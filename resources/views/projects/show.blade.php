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
                        <div class="p-6 rounded-xl border {{ $liveStatus === 'verified' ? 'bg-emerald-50/50 border-emerald-100' : ($liveStatus === 'pending' ? 'bg-amber-50/50 border-amber-100' : 'bg-red-50 border-red-100') }}">
                            <p class="text-[10px] font-bold {{ $liveStatus === 'verified' ? 'text-emerald-600' : ($liveStatus === 'pending' ? 'text-amber-600' : 'text-red-600') }} uppercase tracking-widest mb-2">Live Status</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 {{ $liveStatus === 'verified' ? 'bg-emerald-500' : ($liveStatus === 'pending' ? 'bg-amber-500 animate-pulse' : 'bg-red-500') }} rounded-full shadow-sm"></span>
                                <p class="text-sm font-bold {{ $liveStatus === 'verified' ? 'text-emerald-800' : ($liveStatus === 'pending' ? 'text-amber-800' : 'text-red-800') }}">
                                    {{ $statusText }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ showKey: false, copied: false }">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Project Authentication Key</label>
                    <div class="flex items-center gap-4 bg-gray-900 p-5 rounded-xl border border-white/5 shadow-inner">
                        <code class="flex-1 font-mono text-sm text-emerald-400 truncate tracking-widest" x-text="showKey ? '{{ $project->tracking_id }}' : '•••••••••••••••••••••••••••••••••'"></code>
                        <div class="flex items-center gap-4 border-l border-white/10 pl-5">
                            <button @click="showKey = !showKey" class="text-gray-400 hover:text-white transition">
                                <svg x-show="!showKey" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showKey" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04a11.959 11.959 0 012.316-2.507m2.316-2.316A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-4.225-4.225l-4.703-4.703m0 0L9 9m4.775 4.775L15 15M9 9l-4.725-4.725M12 12L9 9"></path></svg>
                            </button>
                            <button @click="
                                let txt = '{{ $project->tracking_id }}';
                                if (navigator.clipboard && window.isSecureContext) {
                                    navigator.clipboard.writeText(txt);
                                } else {
                                    let textArea = document.createElement('textarea');
                                    textArea.value = txt;
                                    textArea.style.position = 'fixed';
                                    textArea.style.opacity = '0';
                                    document.body.appendChild(textArea);
                                    textArea.focus();
                                    textArea.select();
                                    document.execCommand('copy');
                                    document.body.removeChild(textArea);
                                }
                                copied = true;
                                setTimeout(() => copied = false, 2000);
                            " class="text-gray-400 hover:text-emerald-400 transition flex items-center gap-1">
                                <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <svg x-show="copied" class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span x-show="copied" class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest" style="display: none;">Copied!</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Analytics -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                    @php
                        $limit = Auth::user()->event_limit ?? 10000;
                        $count = $accountTotalEvents ?? 0;
                        $percent = $limit > 0 ? min(($count / $limit) * 100, 100) : 0;
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
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest text-center">{{ number_format($count) }} / {{ number_format($limit) }} events tracked</p>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-gray-50">
                        <div class="flex justify-between text-sm font-bold">
                            <span class="text-gray-400 uppercase tracking-widest text-[10px]">Project Contribution</span>
                            <span class="text-gray-900">{{ number_format($totalEvents) }} ev</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold">
                            <span class="text-gray-400 uppercase tracking-widest text-[10px]">Account Headroom</span>
                            <span class="text-emerald-600">+{{ number_format(max(0, (Auth::user()->event_limit ?? 10000) - $accountTotalEvents)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Analytics Overview Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Analytics Overview</h3>
                <select onchange="window.location.href='?period='+this.value" class="text-xs font-bold text-gray-500 border-gray-200 rounded-lg bg-gray-50 px-3 py-2 outline-none focus:ring-emerald-500 transition">
                    <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="last_7_days" {{ request('period', 'last_7_days') === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="last_30_days" {{ request('period') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="current_month" {{ request('period') === 'current_month' ? 'selected' : '' }}>Current Month</option>
                    <option value="previous_month" {{ request('period') === 'previous_month' ? 'selected' : '' }}>Previous Month</option>
                    <option value="full_year" {{ request('period') === 'full_year' ? 'selected' : '' }}>Full Year</option>
                    <option value="last_year" {{ request('period') === 'last_year' ? 'selected' : '' }}>Last Year</option>
                </select>
            </div>
            
            <div class="p-6 md:p-8">
                <!-- Stats Cards Row -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Total Events</p>
                        <p class="text-2xl font-black text-gray-900 leading-none">{{ number_format($totalEvents) }}</p>
                    </div>
                    <div class="bg-emerald-50/50 p-5 rounded-xl border border-emerald-100">
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide mb-2">Successful</p>
                        <p class="text-2xl font-black text-emerald-600 leading-none">{{ number_format($successfulEvents) }}</p>
                    </div>
                    <div class="bg-red-50/50 p-5 rounded-xl border border-red-100">
                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-wide mb-2">Failed</p>
                        <p class="text-2xl font-black text-red-600 leading-none">{{ number_format($failedEvents) }}</p>
                    </div>
                    <div class="bg-amber-50/50 p-5 rounded-xl border border-amber-100">
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wide mb-2">Pending</p>
                        <p class="text-2xl font-black text-amber-600 leading-none">{{ number_format($pendingEvents) }}</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">Blocked</p>
                        <p class="text-2xl font-black text-gray-900 leading-none">{{ number_format($blockedEvents) }}</p>
                    </div>
                    <div class="bg-purple-50/50 p-5 rounded-xl border border-purple-100 relative overflow-hidden">
                        <span class="absolute top-1 right-1 text-[7px] font-bold bg-white px-1.5 py-0.5 rounded-full shadow-sm text-purple-600 uppercase">New</span>
                        <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wide mb-2">Duplicated</p>
                        <p class="text-2xl font-black text-purple-600 leading-none">{{ number_format($duplicatedEvents) }}</p>
                    </div>
                </div>

                <!-- Main Chart Area -->
                <div class="h-[300px] w-full bg-white rounded-2xl border border-gray-200 flex flex-col relative overflow-hidden p-6 pt-16">
                    <div class="flex-1 flex items-end justify-between gap-2 md:gap-4 relative z-10 pb-6 border-b border-gray-100">
                        @foreach($chartData as $date => $data)
                            @php
                                $successHeight = $maxChartValue > 0 ? ($data['successful'] / $maxChartValue) * 100 : 0;
                                $blockedHeight = $maxChartValue > 0 ? ($data['blocked'] / $maxChartValue) * 100 : 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group/bar relative">
                                <div class="w-full max-w-[40px] flex flex-col justify-end h-full bg-gray-50 rounded-t-md relative hover:bg-gray-100 transition-colors">
                                    <div class="w-full bg-purple-500 rounded-t-sm transition-all duration-500 shadow-sm" style="height: {{ $blockedHeight }}%"></div>
                                    <div class="w-full bg-emerald-500 transition-all duration-500 shadow-sm {{ $blockedHeight == 0 ? 'rounded-t-sm' : '' }}" style="height: {{ $successHeight }}%"></div>
                                </div>
                                <span class="text-[9px] font-bold text-gray-400 uppercase whitespace-nowrap">{{ $data['day_name'] }}</span>
                                
                                <!-- Tooltip -->
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] font-bold py-1.5 px-3 rounded-lg opacity-0 group-hover/bar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-20 shadow-xl">
                                    {{ number_format($data['total']) }} Events
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] absolute bottom-4 left-1/2 -translate-x-1/2">Temporal Node Distribution</p>
                    
                    <!-- Legend -->
                    <div class="absolute top-4 right-6 flex items-center gap-4 z-20">
                        <div class="flex items-center gap-2"><span class="w-3 h-1 bg-emerald-500 rounded-full shadow-sm"></span> <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Successful</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-1 bg-purple-500 rounded-full shadow-sm"></span> <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Blocked</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analytics Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Performance Analytics</h3>
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Real-time health monitoring for your event clusters.</p>
                </div>
                <div class="flex items-center gap-3">
                    <select onchange="window.location.href='?period='+this.value" class="text-xs font-bold text-gray-500 border-gray-200 rounded-lg bg-gray-50 px-3 py-2 outline-none focus:ring-emerald-500 transition">
                        <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_7_days" {{ request('period', 'last_7_days') === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ request('period') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="current_month" {{ request('period') === 'current_month' ? 'selected' : '' }}>Current Month</option>
                        <option value="previous_month" {{ request('period') === 'previous_month' ? 'selected' : '' }}>Previous Month</option>
                        <option value="full_year" {{ request('period') === 'full_year' ? 'selected' : '' }}>Full Year</option>
                        <option value="last_year" {{ request('period') === 'last_year' ? 'selected' : '' }}>Last Year</option>
                    </select>
                    <a href="{{ route('projects.events', $project->id) }}" class="px-5 py-2.5 bg-gray-900 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-black transition inline-block text-center flex items-center justify-center">All Events</a>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse($performanceStats as $index => $stat)
                    <!-- Event Specific Card -->
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-6 hover:border-emerald-200 transition-colors">
                        <div class="flex items-center gap-2 mb-4">
                             <div class="w-1.5 h-1.5 {{ ['bg-emerald-500', 'bg-purple-500', 'bg-blue-500', 'bg-amber-500', 'bg-rose-500'][$index % 5] }} rounded-full"></div>
                             <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">{{ $stat->event_name }}</span>
                        </div>
                        <p class="text-3xl font-black text-gray-900">{{ number_format($stat->total) }}</p>
                    </div>
                    @empty
                    <div class="lg:col-span-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 p-8 text-center">
                        <p class="text-sm font-bold text-gray-500">No event data collected in the last 7 days.</p>
                    </div>
                    @endforelse

                    <!-- Performance Chart Mockup -->
                    <div class="lg:col-span-4 bg-gray-50/30 rounded-2xl border border-gray-100 h-[300px] flex items-center justify-center relative overflow-hidden mt-4">
                         <div class="w-full h-full p-6 flex flex-col justify-between relative z-10">
                             <div class="flex-1 border-b border-gray-100 flex items-end gap-2 md:gap-4 px-2 md:px-4">
                                  @foreach($chartData as $date => $data)
                                      @php
                                          $height = $maxChartValue > 0 ? ($data['total'] / $maxChartValue) * 100 : 0;
                                          $height = max(5, $height); // Ensure tiny bars are visible
                                      @endphp
                                      <div class="flex-1 bg-emerald-200 rounded-t-md hover:bg-emerald-300 transition-colors relative group" style="height: {{ $height }}%">
                                          <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] font-bold py-1.5 px-3 rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-20 shadow-xl">
                                              {{ number_format($data['total']) }} Events
                                          </div>
                                      </div>
                                  @endforeach
                             </div>
                             <div class="pt-4 flex justify-between text-[8px] font-bold text-gray-400 uppercase tracking-widest">
                                 @foreach($chartData as $date => $data)
                                     <span class="flex-1 text-center truncate">{{ \Carbon\Carbon::parse($date)->format('M d') }}</span>
                                 @endforeach
                             </div>
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

    <!-- Modal is now centrally handled -->
</x-app-layout>
