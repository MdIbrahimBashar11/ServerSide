<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 pb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Account Settings</h1>
                <p class="text-base text-gray-600 mt-2">Manage your profile information, security preferences, and account privacy.</p>
            </div>
        </div>

        <div class="max-w-4xl space-y-12 pb-24">
            <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
