<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        
        <div class="border-b border-gray-100 pb-16">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-[0.9] font-outfit uppercase italic tracking-tighter mb-4">PROFILE <span class="text-emerald-500">CONFIGURATION</span></h1>
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em] flex items-center gap-3 italic">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Secure Identity Matrix & Personal Protocol Settings
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Name Settings -->
            <div class="bg-white p-12 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="flex items-center gap-5 mb-12">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-emerald-400 shadow-xl rotate-3 group-hover:rotate-12 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 font-outfit uppercase italic tracking-tighter">Identity</h3>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em] italic opacity-50">Legal Entity Synchronization</p>
                    </div>
                </div>
                
                <form action="{{ route('settings.profile') }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic">Registry Label</label>
                        <input type="text" disabled value="{{ $user->name }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-400 cursor-not-allowed shadow-inner opacity-50">
                    </div>
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-emerald-500 transition-colors">Proposed Identity</label>
                        <input type="text" name="name" placeholder="Enter new moniker" class="w-full bg-gray-50/50 border border-gray-100 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 rounded-2xl py-4 px-6 text-sm font-bold shadow-inner placeholder-slate-300 transition-all">
                    </div>
                    
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.4em] shadow-xl transition-all flex items-center gap-4 italic active:scale-95 group">
                        Update_Identity
                    </button>
                </form>
            </div>

            <!-- Email Settings -->
            <div class="bg-white p-12 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="flex items-center gap-5 mb-12">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-blue-400 shadow-xl -rotate-3 group-hover:rotate-0 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 font-outfit uppercase italic tracking-tighter">Communication</h3>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em] italic opacity-50">Signal Destination Registry</p>
                    </div>
                </div>
                
                <form action="{{ route('settings.profile') }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="name" value="{{ $user->name }}">

                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic">Registered Origin</label>
                        <input type="text" disabled value="{{ $user->email }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-400 cursor-not-allowed shadow-inner opacity-50">
                    </div>
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-emerald-500 transition-colors">Alternative Protocol</label>
                        <input type="email" name="email" placeholder="New communication node" class="w-full bg-gray-50/50 border border-gray-100 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 rounded-2xl py-4 px-6 text-sm font-bold shadow-inner placeholder-slate-300 transition-all">
                    </div>

                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-emerald-500 transition-colors">Authorization Cipher</label>
                        <input type="password" name="current_password" placeholder="Verify cipher to proceed" class="w-full bg-slate-900 border border-white/5 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 rounded-2xl py-4 px-6 text-sm font-mono text-emerald-400 shadow-xl transition-all">
                    </div>
                    
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.4em] shadow-xl transition-all flex items-center gap-4 italic active:scale-95 group">
                        Patch_Signal
                    </button>
                </form>
            </div>

            <!-- Password Settings -->
            <div class="bg-white p-12 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="flex items-center gap-5 mb-12">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-rose-400 shadow-xl rotate-6 group-hover:rotate-0 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 font-outfit uppercase italic tracking-tighter">Protection</h3>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em] italic opacity-50">Encryption Key Rotation</p>
                    </div>
                </div>
                
                <form action="{{ route('password.update') }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PUT')
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-rose-500 transition-colors">Legacy Cipher</label>
                        <input type="password" name="current_password" placeholder="Existing security key" class="w-full bg-gray-50/50 border border-gray-100 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-2xl py-4 px-6 text-sm font-bold shadow-inner placeholder-slate-300 transition-all">
                    </div>
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-rose-500 transition-colors">Quantum Cipher</label>
                        <input type="password" name="password" placeholder="New security vector (8+ chars)" class="w-full bg-gray-50/50 border border-gray-100 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-2xl py-4 px-6 text-sm font-bold shadow-inner placeholder-slate-300 transition-all">
                    </div>

                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-rose-500 transition-colors">Cipher Validation</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm security vector" class="w-full bg-gray-50/50 border border-gray-100 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-2xl py-4 px-6 text-sm font-bold shadow-inner placeholder-slate-300 transition-all">
                    </div>
                    
                    <button type="submit" class="bg-slate-900 hover:bg-rose-500 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.4em] shadow-xl transition-all flex items-center gap-4 italic active:scale-95 group">
                        Rotate_Keys
                    </button>
                </form>
            </div>

            <!-- Phone Settings -->
            <div class="bg-white p-12 rounded-[3.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="flex items-center gap-5 mb-12">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-amber-400 shadow-xl rotate-12 group-hover:rotate-0 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 font-outfit uppercase italic tracking-tighter">Telemetry</h3>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em] italic opacity-50">Crisis Signal Endpoint</p>
                    </div>
                </div>
                
                <form action="{{ route('settings.phone') }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PATCH')
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic">Active Endpoint</label>
                        <input type="text" disabled value="{{ $user->phone_number ?? 'NULL_VOID' }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-slate-400 cursor-not-allowed shadow-inner opacity-50">
                    </div>
                    
                    <div class="group/input">
                        <label class="block font-black text-[10px] text-slate-400 uppercase tracking-[0.3em] mb-4 italic group-focus-within/input:text-amber-500 transition-colors">Primary Signal Lane</label>
                        <input type="text" name="phone_number" placeholder="+1 (000) 000-0000" value="{{ $user->phone_number }}" class="w-full bg-gray-50/50 border border-gray-100 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500/50 rounded-2xl py-4 px-6 text-sm font-bold shadow-inner placeholder-slate-300 transition-all">
                        <div class="mt-8 p-6 bg-slate-900 rounded-[1.5rem] border border-white/5 relative overflow-hidden group/alert">
                            <div class="absolute inset-0 bg-amber-500/5 opacity-0 group-hover/alert:opacity-100 transition-opacity"></div>
                            <p class="text-[9px] text-amber-400 font-black uppercase tracking-[0.4em] italic leading-relaxed relative z-10">Verification required for high-priority crisis SMS alerts. <a href="#" class="underline ml-2">Protocols &rarr;</a></p>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-slate-900 hover:bg-amber-500 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.4em] shadow-xl transition-all flex items-center gap-4 italic active:scale-95 group">
                        Update_Telemetry
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
