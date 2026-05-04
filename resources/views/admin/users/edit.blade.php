<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Modify User Details</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Change name, email, role, and current status of user account.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 border border-gray-200 px-5 py-3 rounded-xl transition">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-emerald-500 focus:border-emerald-500 font-medium transition" />
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-emerald-500 focus:border-emerald-500 font-medium transition" />
                    @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Role Type</label>
                        <select name="role" id="role"
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-emerald-500 focus:border-emerald-500 font-medium transition">
                            <option value="tenant" {{ old('role', $user->role) === 'tenant' ? 'selected' : '' }}>Tenant</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Account Status</label>
                        <select name="status" id="status"
                                class="block w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl py-3.5 px-4 focus:ring-emerald-500 focus:border-emerald-500 font-medium transition">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 flex justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-widest transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-md transition active:scale-95">
                        Save Account Updates
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
