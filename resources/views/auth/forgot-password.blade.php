<x-guest-layout maxWidth="max-w-md">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Recover Key</h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-3">Reset your secure infrastructure access</p>
    </div>

    <div class="mb-8 text-[11px] font-bold text-gray-400 leading-relaxed uppercase tracking-wider text-center">
        {{ __('Forgot your access key? No problem. Just let us know your gateway endpoint and we will send a recovery command to your inbox.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-3">Gateway Endpoint</label>
            <input id="email" class="block w-full bg-gray-50/50 border border-gray-100 rounded-2xl py-4.5 px-6 text-gray-950 font-bold focus:ring-2 focus:ring-emerald-600 shadow-inner text-sm transition" type="email" name="email" :value="old('email')" required autofocus placeholder="ops@network.io" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button class="w-full py-5 bg-gray-900 text-white rounded-2xl font-extrabold text-xs uppercase tracking-[0.3em] shadow-xl transition hover:bg-black active:scale-[0.98] flex items-center justify-center gap-3">
                Send Recovery Command
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </button>
        </div>

        <div class="text-center pt-8 border-t border-gray-50">
             <a href="{{ route('login') }}" class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest hover:text-emerald-700 transition">Back to Login Gateway</a>
        </div>
    </form>
</x-guest-layout>
