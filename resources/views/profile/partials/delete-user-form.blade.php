<section class="space-y-10">
    <header>
        <h2 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-widest text-xs">
            Danger Zone
        </h2>

        <p class="mt-4 text-sm text-gray-600 font-bold uppercase tracking-widest leading-relaxed">
            Once your account is deleted, all of its records and data will be permanently removed.
        </p>
    </header>

    <div class="pt-4">
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-8 py-4 bg-red-50 text-red-700 border border-red-200 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-100 transition-all active:scale-95"
        >Permamently Delete Account</button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 bg-white">
            @csrf
            @method('delete')

            <div class="mb-10 text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Are you absolutely sure?</h2>
                <p class="text-sm text-gray-600 font-bold uppercase tracking-widest leading-relaxed opacity-60">
                    This action is final. Please enter your security code to confirm termination of all associated node data and project manifests.
                </p>
            </div>

            <div class="mt-8 space-y-6">
                <x-input-label for="password" value="{{ __('Security Code') }}" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full border-gray-300 rounded-xl py-4 px-6 font-bold text-gray-900 focus:ring-red-600 focus:border-red-600 transition"
                    placeholder="Enter Password"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-12 flex justify-end items-center gap-6">
                <button type="button" x-on:click="$dispatch('close')" class="text-xs font-bold text-gray-500 hover:text-gray-900 uppercase tracking-widest transition">
                    Abort
                </button>
                <button type="submit" class="px-10 py-4 bg-red-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-red-700 transition active:scale-95">
                    Terminal Shutdown
                </button>
            </div>
        </form>
    </x-modal>
</section>
