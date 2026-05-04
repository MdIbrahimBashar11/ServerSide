<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
        
        @include('projects._nav')

        <div class="border-b border-gray-200 pb-10">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Project Settings</h1>
            <p class="text-base text-gray-600 mt-2">Configure your project identity and API destination endpoints.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Left Side Forms -->
            <div class="lg:col-span-2 space-y-12">
                
                @if(session('status'))
                    <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 font-bold text-sm flex items-center gap-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('projects.update', $project->id) }}" method="POST" class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm space-y-10">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-l-4 border-emerald-600 pl-4 uppercase tracking-widest text-xs">Core Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Project Name</label>
                                <input name="name" type="text" value="{{ $project->name }}" class="block w-full border-gray-300 rounded-xl py-3 px-4 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold" required />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Website URL</label>
                                <input name="website_url" type="url" value="{{ $project->website_url }}" class="block w-full border-gray-300 rounded-xl py-3 px-4 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold" placeholder="https://www.example.com" required />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tracking Subdomain</label>
                                <input name="custom_domain" type="text" value="{{ $project->custom_domain }}" class="block w-full border-gray-300 rounded-xl py-3 px-4 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold" placeholder="track.example.com" required />
                                <p class="text-[10px] text-gray-500 font-bold mt-2">Example: track.yourdomain.com (Do not include http/https)</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Primary Platform</label>
                            <select name="platform" class="block w-full border-gray-300 rounded-xl py-3 px-4 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold">
                                <option value="laravel" {{ $project->platform == 'laravel' ? 'selected' : '' }}>Laravel</option>
                                <option value="wordpress" {{ $project->platform == 'wordpress' ? 'selected' : '' }}>WordPress</option>
                                <option value="shopify" {{ $project->platform == 'shopify' ? 'selected' : '' }}>Shopify</option>
                                <option value="custom" {{ $project->platform == 'custom' ? 'selected' : '' }}>Custom Integration</option>
                            </select>
                        </div>

                        <div x-data="{ showKey: false, copied: false }">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Authentication Key</label>
                            <div class="relative">
                                <input :type="showKey ? 'text' : 'password'" value="{{ $project->tracking_id }}" readonly class="block w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl py-3 px-4 cursor-not-allowed font-mono text-sm pr-32" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 gap-3">
                                    <button type="button" @click="showKey = !showKey" class="text-gray-400 hover:text-gray-700 transition">
                                        <svg x-show="!showKey" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="showKey" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04a11.959 11.959 0 012.316-2.507m2.316-2.316A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-4.225-4.225l-4.703-4.703m0 0L9 9m4.775 4.775L15 15M9 9l-4.725-4.725M12 12L9 9"></path></svg>
                                    </button>
                                    <button type="button" @click="
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
                                    " class="text-gray-400 hover:text-emerald-600 transition flex items-center gap-1">
                                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        <svg x-show="copied" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span x-show="copied" class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest" style="display: none;">Copied!</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="bg-gray-900 hover:bg-black text-white px-8 py-4 rounded-xl font-bold text-sm shadow-md transition-all active:scale-95">
                            Save Changes
                        </button>
                    </div>
                </form>

                @php
                    $fbDestination = $project->destinations()->where('platform', 'fb_capi')->first();
                    $ttDestination = $project->destinations()->where('platform', 'tiktok')->first();
                @endphp

                <form action="{{ route('projects.destinations.update', $project->id) }}" method="POST" class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm space-y-10">
                    @csrf
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-l-4 border-blue-600 pl-4 uppercase tracking-widest text-xs">API Destinations</h3>
                        <p class="text-sm text-gray-600 mb-10">Configure where your event data should be routed after it reaches our server.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Facebook CAPI -->
                        <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 group">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">FB</div>
                                <h4 class="font-bold text-gray-900">Meta / Facebook CAPI</h4>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pixel ID</label>
                                    <input name="fb_pixel_id" type="text" value="{{ $fbDestination?->dataset_id }}" class="w-full border-gray-300 rounded-lg text-sm font-bold py-3 px-4 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="1029384..." />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Access Token</label>
                                    <input name="fb_access_token" type="password" value="{{ $fbDestination?->access_token }}" class="w-full border-gray-300 rounded-lg text-sm font-bold py-3 px-4 focus:ring-blue-600 focus:border-blue-600 transition" placeholder="••••••••" />
                                </div>
                            </div>
                        </div>

                        <!-- TikTok eAPI -->
                        <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 group">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 rounded-lg bg-black flex items-center justify-center text-white font-bold text-sm shadow-md">TK</div>
                                <h4 class="font-bold text-gray-900">TikTok API</h4>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pixel ID</label>
                                    <input name="tt_pixel_id" type="text" value="{{ $ttDestination?->dataset_id }}" class="w-full border-gray-300 rounded-lg text-sm font-bold py-3 px-4 focus:ring-gray-900 focus:border-gray-900 transition" placeholder="CEJ..." />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Access Token</label>
                                    <input name="tt_access_token" type="password" value="{{ $ttDestination?->access_token }}" class="w-full border-gray-300 rounded-lg text-sm font-bold py-3 px-4 focus:ring-gray-900 focus:border-gray-900 transition" placeholder="••••••••" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-gray-900 hover:bg-black text-white px-8 py-3 rounded-xl text-sm font-bold shadow-md transition-all active:scale-95">
                            Update Destinations
                        </button>
                    </div>
                </form>

            </div>

            <!-- Right Side Widgets -->
            <div class="space-y-12">
                <!-- Status Check -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-8 border-l-4 border-emerald-600 pl-4">System Health</h3>
                    
                    <div class="space-y-6 mb-10">
                        <div class="flex justify-between items-center border-b border-gray-50 pb-4">
                            <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Status</span>
                            <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">Optimal</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-50 pb-4">
                            <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Domain</span>
                            <span class="text-xs font-bold text-gray-900 truncate max-w-[120px]">{{ $project->website_url ? rtrim(str_replace(['http://', 'https://'], '', $project->website_url), '/') : 'N/A' }}</span>
                        </div>
                        @php
                            $limit = Auth::user()->event_limit ?? 10000;
                            $count = \App\Domains\Projects\Models\Event::where('project_id', $project->id)->count();
                            $percentage = ($count / $limit) * 100;
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Project Usage</span>
                            <span class="text-xs font-bold text-gray-900">{{ number_format($count) }} / {{ number_format($limit) }}</span>
                        </div>
                        
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden mt-1">
                            <div class="bg-emerald-600 h-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Diagnostics -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-600 border border-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Diagnostics</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Test ID Code</label>
                            <input type="text" class="block w-full border-gray-300 rounded-lg py-3 px-4 text-gray-900 font-bold text-xs" placeholder="TEST-0000" />
                        </div>
                        <button class="w-full bg-gray-50 hover:bg-gray-100 text-gray-900 py-3 rounded-lg font-bold text-xs border border-gray-200 transition">
                            Run Connection Test
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
