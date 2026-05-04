<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Customer Management</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Search and manage user/tenant accounts registered on the platform.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-900 text-sm font-bold shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-80">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl pl-4 pr-10 py-3 outline-none focus:ring-emerald-500 focus:border-emerald-500 transition" />
                    </div>
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-md transition-all active:scale-95">
                        Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest border border-gray-200 shadow-sm transition">
                            Clear
                        </a>
                    @endif
                </form>
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                    {{ $users->total() }} total users
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs uppercase bg-gray-50/80 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Tenant / User Info</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Role</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-right font-bold tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 flex flex-col justify-center">
                                <span class="font-bold text-gray-900">{{ $user->name }}</span>
                                <span class="text-xs text-gray-500 font-medium">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold rounded-md uppercase tracking-wider">Superadmin</span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-md uppercase tracking-wider">Tenant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">Suspended</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg transition">VIEW</a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg transition">EDIT</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->status === 'active')
                                            <button type="submit" class="text-xs font-bold text-rose-700 hover:text-rose-800 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-lg transition">SUSPEND</button>
                                        @else
                                            <button type="submit" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg transition">ACTIVATE</button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-700 hover:text-red-800 bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg transition">DELETE</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
