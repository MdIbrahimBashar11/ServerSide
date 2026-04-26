<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-zinc-100 leading-tight tracking-tight">
            {{ __('Endpoints & Destinations') }}
        </h2>
    </x-slot>

    <style>
        .glass-card {
            background: rgba(24, 24, 27, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.05);
        }
        
        .code-block {
            background-color: #0d0d0f;
            border: 1px solid #27272a;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        .code-text .keyword { color: #c678dd; } /* Purple */
        .code-text .string { color: #98c379; } /* Green */
        .code-text .property { color: #d19a66; } /* Orange */
        .code-text .punctuation { color: #abb2bf; } /* Gray */
    </style>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Tracking Payload Implementation -->
            <div class="glass-card rounded-3xl p-8 lg:p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-48 h-48 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-2 relative z-10 tracking-tight">Deploy Frontend Engine</h3>
                <p class="text-zinc-400 mb-6 max-w-2xl relative z-10">Paste this highly optimized vanilla javascript payload inside the <code>&lt;head&gt;</code> of your website. It binds automatically to your active project.</p>
                
                <div class="relative z-10" x-data="{ copied: false, code: `<!-- ServerTrack Protocol -->\n<script>\n    window.ServerTrackEnv = {\n        tracking_id: '{{ $project->tracking_id }}',\n        endpoint: '{{ url('/api/track-event') }}'\n    };\n</script>\n<script src=\x22{{ url('/js/track.js') }}\x22 async></script>` }">
                    <div class="code-block rounded-2xl p-6 relative overflow-hidden group">
                        <pre class="text-sm code-text overflow-x-auto whitespace-pre-wrap leading-relaxed"><span class="punctuation">&lt;!-- ServerTrack Protocol --&gt;</span>
<span class="punctuation">&lt;script&gt;</span>
    window.<span class="property">ServerTrackEnv</span> <span class="punctuation">=</span> {
        tracking_id: <span class="string">'{{ $project->tracking_id }}'</span>,
        endpoint: <span class="string">'{{ url('/api/track-event') }}'</span>
    };
<span class="punctuation">&lt;/script&gt;</span>
<span class="punctuation">&lt;script</span> src=<span class="string">"{{ url('/js/track.js') }}"</span> async<span class="punctuation">&gt;&lt;/script&gt;</span></pre>
                        
                        <button @click="navigator.clipboard.writeText(code); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="absolute top-4 right-4 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-lg px-4 py-2 text-xs font-semibold uppercase tracking-widest transition flex items-center gap-2 border border-zinc-700 focus:outline-none">
                            <span x-show="!copied"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Copy Snippet</span>
                            <span x-show="copied" x-cloak class="text-emerald-400 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CRM Webhooks Configuration -->
            <div class="glass-card rounded-3xl p-8 relative">
                <header class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white tracking-tight">Active HTTP Webhooks</h3>
                        <p class="text-xs text-zinc-500 font-medium">Stream tracking payloads concurrently to custom CRMs securely payload.</p>
                    </div>
                </header>
                
                @php $webhook = clone collect($destinations ?? [])->where('platform', 'webhook')->first(); @endphp
                <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf
                    <div>
                        <label class="block font-medium text-sm text-zinc-300">Target Webhook URL</label>
                        <input type="url" name="dataset_id" value="{{ $webhook->dataset_id ?? '' }}" class="mt-2 block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl shadow-inner focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="https://mycrm.com/api/catch-webhook">
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-zinc-300">Authorization Bearer Token (Optional)</label>
                        <input type="password" name="access_token" value="{{ $webhook->access_token ?? '' }}" class="mt-2 block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl shadow-inner focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Secret Key">
                        <p class="text-xs text-zinc-500 mt-2">Appended to the header. We also automatically transmit a SHA-256 HMAC utilizing this key.</p>
                    </div>
                    <div class="md:col-span-2 flex justify-end pt-4 border-t border-zinc-800/50">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-600/20 transition transform hover:-translate-y-0.5">Register Target Listener</button>
                    </div>
                </form>
            </div>

            <!-- Advertising API Settings Container -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Meta CAPI Section -->
                <div class="glass-card rounded-3xl p-8 border border-blue-500/10 hover:border-blue-500/30 transition group">
                    <header class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-[#1877F2]/10 rounded-xl flex items-center justify-center text-[#1877F2] group-hover:scale-110 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white tracking-tight">Meta Conversion API</h3>
                    </header>
                    @php $fb = clone collect($destinations ?? [])->where('platform', 'fb_capi')->first(); @endphp
                    <form action="#" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block font-medium text-sm text-zinc-300">Data Set ID (Pixel)</label>
                                <input type="text" value="{{ $fb->dataset_id ?? '' }}" class="mt-2 block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl shadow-inner focus:ring-blue-500 focus:border-blue-500" placeholder="1029384756">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-zinc-300">System Access Token</label>
                                <input type="password" value="{{ $fb->access_token ?? '' }}" class="mt-2 block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl shadow-inner focus:ring-blue-500 focus:border-blue-500" placeholder="EAAI...">
                            </div>
                            <button type="submit" class="w-full bg-[#1877F2] hover:bg-[#1877F2]/90 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-500/20 transition">Sync CAPI Credentials</button>
                        </div>
                    </form>
                </div>

                <!-- GA4 Protocol Section -->
                <div class="glass-card rounded-3xl p-8 border border-amber-500/10 hover:border-amber-500/30 transition group">
                    <header class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-500 group-hover:scale-110 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-2.8 5.36-7.84 5.36-4.72 0-8.6-3.88-8.6-8.6 0-4.72 3.88-8.6 8.6-8.6 2.68 0 4.68 1.12 5.76 2.16l2.56-2.52C18.64 0 15.84-1 12.48-1 5.36-1 0 4.76 0 11.88c0 7.12 5.36 12.88 12.48 12.88 7.36 0 12.24-5.16 12.24-12.48 0-.84-.08-1.56-.24-2.28H12.48v.92z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white tracking-tight">Google Analytics 4</h3>
                    </header>
                    @php $ga = clone collect($destinations ?? [])->where('platform', 'ga4')->first(); @endphp
                    <form action="#" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block font-medium text-sm text-zinc-300">Measurement ID</label>
                                <input type="text" value="{{ $ga->dataset_id ?? '' }}" class="mt-2 block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl shadow-inner focus:ring-amber-500 focus:border-amber-500" placeholder="G-XXXXXXXX">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-zinc-300">API Secret</label>
                                <input type="password" value="{{ $ga->access_token ?? '' }}" class="mt-2 block w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl shadow-inner focus:ring-amber-500 focus:border-amber-500" placeholder="Secret generated in GA4 Admin...">
                            </div>
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-zinc-900 py-3 rounded-xl font-bold shadow-lg shadow-amber-500/20 transition">Sync Measurement Protocol</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
