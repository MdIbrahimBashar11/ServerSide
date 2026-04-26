<x-guest-layout maxWidth="max-w-md">
    <div class="mb-12 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Access Gateway</h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-3">Authorize your secure tracking session</p>
    </div>

    <!-- Social Authentication -->
    <div class="grid grid-cols-2 gap-4 mb-10">
        <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-3 py-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition shadow-sm active:scale-[0.98]">
            <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            <span class="text-[10px] font-extrabold text-gray-900 uppercase tracking-widest">Google</span>
        </a>
        <a href="{{ route('social.redirect', 'github') }}" class="flex items-center justify-center gap-3 py-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition shadow-sm active:scale-[0.98]">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
            <span class="text-[10px] font-extrabold text-gray-900 uppercase tracking-widest">GitHub</span>
        </a>
    </div>

    <div class="relative mb-10 text-center">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-50"></div></div>
        <span class="relative bg-white px-6 text-[10px] font-bold text-gray-300 uppercase tracking-widest italic">Or Identity Connection</span>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-3">Identity Endpoint</label>
            <input id="email" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition shadow-inner text-sm" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-3 text-[10px] font-extrabold uppercase tracking-widest">
                <label for="password" class="text-gray-400">Access Key</label>
                @if (Route::has('password.request'))
                    <a class="text-emerald-500 hover:text-emerald-600 transition" href="{{ route('password.request') }}">Lost Key?</a>
                @endif
            </div>
            <input id="password" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition shadow-inner text-sm" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button class="w-full py-5 bg-gray-900 text-white rounded-2xl font-extrabold text-xs uppercase tracking-[0.4em] shadow-xl shadow-gray-900/10 transition hover:bg-black active:scale-[0.98] flex items-center justify-center gap-3">
                Establish Connection
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </button>
        </div>

        <div class="text-center pt-10 border-t border-gray-50">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                New Infrastructure? <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 ml-2">Initialize Node</a>
            </p>
        </div>
    </form>
</x-guest-layout>
