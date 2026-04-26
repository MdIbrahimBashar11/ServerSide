<section>
    <header class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-l-4 border-blue-600 pl-4 uppercase tracking-widest text-xs">
            Security & Password
        </h2>

        <p class="mt-4 text-sm text-gray-600 font-bold uppercase tracking-widest leading-relaxed">
            Ensure your account is protected with a secure and unique password.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-8">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Security Code')" class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded-xl py-3 px-4 font-bold text-gray-900 focus:ring-blue-600 focus:border-blue-600 transition" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-xl py-3 px-4 font-bold text-gray-900 focus:ring-blue-600 focus:border-blue-600 transition" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-xl py-3 px-4 font-bold text-gray-900 focus:ring-blue-600 focus:border-blue-600 transition" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit" class="px-10 py-4 bg-gray-900 text-white rounded-xl font-bold text-sm shadow-md hover:bg-black transition-all active:scale-95 leading-none">
                Update Security
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-600 uppercase tracking-widest"
                >Security Credentials Updated</p>
            @endif
        </div>
    </form>
</section>
