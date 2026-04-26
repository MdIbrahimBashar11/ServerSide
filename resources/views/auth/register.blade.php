<x-guest-layout maxWidth="max-w-4xl">
    <div class="mb-14 text-center">
        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Initialize Protocol</h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.4em] mt-3">Establish your secure tracking infrastructure</p>
    </div>

    <!-- Social Authentication -->
    <div class="max-w-xl mx-auto mb-16">
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-3 py-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition shadow-sm active:scale-[0.98]">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                <span class="text-[10px] font-extrabold text-gray-900 uppercase tracking-widest">Google Login</span>
            </a>
            <a href="{{ route('social.redirect', 'github') }}" class="flex items-center justify-center gap-3 py-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition shadow-sm active:scale-[0.98]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                <span class="text-[10px] font-extrabold text-gray-900 uppercase tracking-widest">GitHub</span>
            </a>
        </div>
        <div class="relative mt-8 text-center">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-50"></div></div>
            <span class="relative bg-white px-8 text-[10px] font-black text-gray-300 uppercase tracking-[0.3em] italic">Or Deployment Protocol</span>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="{ selectedPlan: {{ $plans->first()->id ?? 0 }} }" class="pb-10">
        @csrf
        <input type="hidden" name="plan_id" :value="selectedPlan">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-12 items-start">
            
            <!-- Left: Identity Details -->
            <div class="space-y-10">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em]">Identity Details</h3>
                </div>
                
                <div>
                    <label for="name" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-3">Gateway Name (Company)</label>
                    <input id="name" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 shadow-inner text-sm transition" type="text" name="name" :value="old('name')" required autofocus placeholder="e.g. Acme Corp" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="email" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-3">Secure Endpoint (Email)</label>
                    <input id="email" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 shadow-inner text-sm transition" type="email" name="email" :value="old('email')" required placeholder="ops@network.io" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-3">Access Key</label>
                        <input id="password" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 shadow-inner text-sm transition" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-3">Verify Key</label>
                        <input id="password_confirmation" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 shadow-inner text-sm transition" type="password" name="password_confirmation" required />
                    </div>
                </div>

                <div class="pt-6">
                    <button class="w-full py-5 bg-gray-900 text-white rounded-2xl font-extrabold text-xs uppercase tracking-[0.4em] shadow-2xl transition hover:bg-black active:scale-[0.98] flex items-center justify-center gap-3">
                        Deploy Node
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </button>
                </div>
                
                <div class="text-center pt-8 border-t border-gray-50">
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest leading-loose">
                        Node already active? <br>
                        <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-black">Login to Synchronize</a>
                    </p>
                </div>
            </div>

            <!-- Right: Infrastructure Selection -->
            <div class="space-y-10">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em]">Infrastructure Tier</h3>
                </div>
                
                <div class="space-y-6">
                    @foreach($plans as $plan)
                        <div @click="selectedPlan = {{ $plan->id }}" 
                             :class="selectedPlan === {{ $plan->id }} ? 'border-emerald-600 bg-emerald-50/20 ring-4 ring-emerald-500/5' : 'border-gray-50 bg-white hover:bg-gray-50'"
                             class="p-8 rounded-[2.5rem] border-2 transition-all cursor-pointer group shadow-sm">
                             
                             <div class="flex justify-between items-start mb-6">
                                 <div>
                                     <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1 group-hover:text-emerald-600 transition" :class="selectedPlan === {{ $plan->id }} && 'text-emerald-600'">{{ $plan->name }}</h4>
                                     <p class="text-2xl font-black text-gray-900 tracking-tighter">৳{{ number_format($plan->price) }} <span class="text-[10px] font-bold text-gray-400 uppercase">/ Mo</span></p>
                                 </div>
                                 <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-all bg-white" :class="selectedPlan === {{ $plan->id }} ? 'border-emerald-600 text-emerald-600 shadow-xl shadow-emerald-600/20' : 'border-gray-100 text-transparent'">
                                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                 </div>
                             </div>

                             <div class="space-y-4">
                                 <div class="flex items-center gap-3">
                                     <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                                     <span class="text-[11px] font-black text-gray-950 uppercase tracking-wide">{{ number_format($plan->event_limit) }} Monthly Events</span>
                                 </div>
                                 <div class="grid grid-cols-1 gap-2 pt-2 border-t border-gray-50">
                                     @if($plan->features)
                                         @foreach(array_slice($plan->features, 0, 3) as $feature)
                                             <div class="flex items-center gap-3 opacity-50">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">{{ $feature }}</span>
                                             </div>
                                         @endforeach
                                     @endif
                                 </div>
                             </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-8 bg-gray-950 rounded-[2rem] border border-white/5 shadow-2xl relative overflow-hidden group">
                     <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none group-hover:scale-110 transition-transform">
                        <svg class="w-32 h-32 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                     </div>
                     <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-3 italic">Enterprise Ops:</p>
                     <p class="text-xs text-white/60 leading-relaxed font-bold italic">Custom high-load clusters (5M+ events) are available. Open a support ticket after node initialization.</p>
                </div>
            </div>
        </div>
    </form>
</x-guest-layout>
