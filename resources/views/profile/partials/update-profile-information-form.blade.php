<section>
    <header class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-l-4 border-emerald-600 pl-4 uppercase tracking-widest text-xs">
            Profile Information
        </h2>

        <p class="mt-4 text-sm text-gray-600 font-bold uppercase tracking-widest leading-relaxed">
            Update your account's profile information and primary email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-8">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-xl py-3 px-4 font-bold text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 rounded-xl py-3 px-4 font-bold text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <p class="text-xs font-bold text-amber-800 uppercase tracking-widest mb-3">
                        Status: Email Unverified
                    </p>

                    <button form="send-verification" class="text-xs font-bold text-amber-600 hover:text-amber-700 underline decoration-2 underline-offset-4 uppercase tracking-widest transition">
                        Re-send verification link
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-4 font-bold text-xs text-emerald-600 uppercase tracking-widest">
                            New link dispatched to your inbox.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit" class="px-10 py-4 bg-gray-900 text-white rounded-xl font-bold text-sm shadow-md hover:bg-black transition-all active:scale-95 leading-none">
                Save Profile
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-600 uppercase tracking-widest"
                >Changes Applied Successfully</p>
            @endif
        </div>
    </form>
</section>
