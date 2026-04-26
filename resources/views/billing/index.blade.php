<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 pb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Billing & Invoices</h1>
                <p class="text-base text-gray-600 mt-2">Manage your subscription, view billing history, and download invoices.</p>
            </div>
        </div>

        <!-- Billing Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Total Invoices</p>
                <div class="flex items-center justify-between">
                    <h4 class="text-3xl font-bold text-gray-900">{{ $user->invoices->count() }}</h4>
                    <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 border border-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-4">Paid Invoices</p>
                <div class="flex items-center justify-between">
                    <h4 class="text-3xl font-bold text-gray-900">{{ $user->invoices()->where('status', 'paid')->count() }}</h4>
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 border border-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4">Unpaid Invoices</p>
                <div class="flex items-center justify-between">
                    <h4 class="text-3xl font-bold text-gray-900">{{ $user->invoices()->where('status', 'unpaid')->count() }}</h4>
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 border border-amber-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 p-8 rounded-xl shadow-md">
                <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-4">Total Paid</p>
                <div class="flex items-center justify-between">
                    <h4 class="text-3xl font-bold text-white tracking-tight">৳{{ number_format($user->invoices()->where('status', 'paid')->sum('amount'), 0) }}</h4>
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-emerald-400 border border-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Current Subscription Plan -->
            <div class="bg-white p-10 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-emerald-700 uppercase tracking-widest mb-10 border-l-4 border-emerald-600 pl-4">Your Active Plan</h3>
                    
                    <div class="mb-10">
                        <h2 class="text-4xl font-bold text-gray-900 mb-2">{{ $user->subscriptionPlan->name ?? 'Free Tier' }}</h2>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest leading-none">Subscription Active</p>
                        </div>
                    </div>

                    <div class="space-y-6 mb-10">
                        <div class="flex items-center gap-4 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm font-bold">Monthly Limit: {{ number_format($user->subscriptionPlan->event_limit ?? 0) }} Events</span>
                        </div>
                        <div class="flex items-center gap-4 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-bold italic">Renewal Date: {{ $user->next_bill_date ? $user->next_bill_date->format('d M, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}#pricing" class="w-full inline-block bg-gray-900 text-white font-bold py-5 rounded-xl text-sm text-center shadow-md hover:bg-black transition-all active:scale-95">
                    Upgrade Subscription
                </a>
            </div>

            <!-- Invoices List -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Billing History</h3>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Detailed list of all account transactions</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white">
                            <tr class="border-b border-gray-100">
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Invoice ID</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none text-center">Status</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Amount</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest leading-none text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($user->invoices->sortByDesc('created_at') as $invoice)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-gray-900 font-mono tracking-tight">#INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $invoice->created_at->format('d M, Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        @if($invoice->status === 'paid')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-widest leading-none">Authorized</span>
                                        @elseif($invoice->status === 'unpaid')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-widest leading-none">Pending Payment</span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-100 uppercase tracking-widest leading-none">{{ strtoupper($invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-bold text-gray-900 tracking-tight">৳{{ number_format($invoice->amount, 0) }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($invoice->status === 'unpaid' || $invoice->status === 'overdue')
                                            <a href="{{ route('billing.select_gateway', $invoice) }}" class="inline-flex py-3 px-6 rounded-lg bg-emerald-600 text-white text-xs font-bold transition hover:bg-emerald-700 active:scale-95 shadow-md">Complete Payment</a>
                                        @else
                                            <a href="{{ route('billing.download', $invoice->id) }}" class="text-xs font-bold text-gray-400 hover:text-gray-900 transition flex items-center justify-end gap-2">
                                                <span>Download PDF</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-32 text-center text-gray-400 font-bold uppercase tracking-widest text-xs opacity-50">
                                        No billing records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
