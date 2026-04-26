<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <div class="flex justify-between items-center bg-[#0a0f1c] text-white p-8 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-600/10 pointer-events-none"></div>
            <div class="relative z-10 w-full flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Subscription Plans</h1>
                    <p class="text-blue-200 font-medium max-w-2xl">Manage pricing, event limits, and dynamically map subscriptions to Stripe billing natively from your deck.</p>
                </div>
                <a href="{{ route('admin.packages.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg font-bold transition shadow-lg shadow-blue-500/30 flex gap-2 items-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create New Plan
                </a>
            </div>
            <!-- Decorative Hexagon -->
            <svg class="absolute -right-10 -bottom-20 w-64 h-64 text-blue-500/10 transform rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 font-bold text-sm shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-5 font-extrabold text-xs text-gray-500 uppercase tracking-widest">Plan Name</th>
                            <th class="p-5 font-extrabold text-xs text-gray-500 uppercase tracking-widest">Price</th>
                            <th class="p-5 font-extrabold text-xs text-gray-500 uppercase tracking-widest">Event Limit</th>
                            <th class="p-5 font-extrabold text-xs text-gray-500 uppercase tracking-widest">Stripe Sync</th>
                            <th class="p-5 font-extrabold text-xs text-gray-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-5 font-bold text-gray-900 border-l-4 border-l-transparent hover:border-l-blue-500 transition-all">
                                    {{ $plan->name }}
                                </td>
                                <td class="p-5 font-bold text-gray-900">
                                    ${{ number_format($plan->price, 2) }}
                                </td>
                                <td class="p-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 tracking-wide">
                                        {{ number_format($plan->event_limit) }} Events
                                    </span>
                                </td>
                                <td class="p-5 text-sm font-medium">
                                    @if($plan->stripe_price_id)
                                        <span class="text-emerald-600 flex items-center gap-1 font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Synced
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">{{ substr($plan->stripe_price_id, 0, 10) }}...</p>
                                    @else
                                        <span class="text-amber-500 flex items-center gap-1 font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Unlinked
                                        </span>
                                    @endif
                                </td>
                                <td class="p-5 flex justify-end gap-2">
                                    <a href="{{ route('admin.packages.edit', $plan->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2 rounded-lg text-sm transition">Edit</a>
                                    <form action="{{ route('admin.packages.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to permanently delete this plan? Active subscriptions via Stripe will NOT be automatically cancelled.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-100 hover:bg-rose-200 text-rose-600 font-bold px-4 py-2 rounded-lg text-sm transition">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-500 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">No Subscription Plans Found</h3>
                                    <p class="text-gray-500 font-medium">Create your first billing package to start monetizing.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
