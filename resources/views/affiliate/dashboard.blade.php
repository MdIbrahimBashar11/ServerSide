<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 border-b border-gray-200 pb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Affiliate Program</h1>
                <p class="text-base text-gray-600 mt-2">Earn recurring commissions by referring new customers.</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Commission Rate</p>
                <p class="text-2xl font-bold text-emerald-600">10% Recurring</p>
            </div>
        </div>

        <!-- Referral Link -->
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Your Referral Link</h3>
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center justify-between">
                    <span class="text-gray-900 font-mono text-sm truncate mr-4">{{ config('app.url') }}?ref={{ $user->affiliate_code }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ config('app.url') }}?ref={{ $user->affiliate_code }}')" class="text-emerald-600 font-bold text-sm hover:text-emerald-700 transition">
                        Copy Link
                    </button>
                </div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Direct attribution for all success sessions</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $statItems = [
                    ['title' => 'Total Referrals', 'value' => $stats['total_referrals'], 'label' => 'Registered Users', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'blue'],
                    ['title' => 'Active Subscriptions', 'value' => $stats['paid_servers'], 'label' => 'Paying Customers', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald'],
                    ['title' => 'Free Trials', 'value' => $stats['free_servers'], 'label' => 'Active Trials', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'color' => 'gray'],
                    ['title' => 'Monthly Earnings', 'value' => '৳'.number_format($stats['this_month_earned'], 0), 'label' => 'This Month', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald'],
                ];
            @endphp

            @foreach($statItems as $item)
            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-gray-600 border border-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $item['title'] }}</p>
                </div>
                <h4 class="text-3xl font-bold text-gray-900">{{ $item['value'] }}</h4>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payouts -->
            <div class="bg-gray-900 p-10 rounded-xl shadow-lg flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-[0.3em] mb-8">Available Balance</h3>
                    <h2 class="text-5xl font-bold text-white tracking-tight mb-8">৳{{ number_format($user->affiliate_balance, 0) }}</h2>
                    
                    <div class="space-y-4 pt-8 border-t border-white/10 mb-10">
                        <div class="flex justify-between items-center text-sm font-bold text-gray-400">
                            <span>Total Earned</span>
                            <span class="text-white">৳{{ number_format($user->affiliate_balance, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-bold text-gray-400">
                            <span>Withdrawn</span>
                            <span class="text-gray-600">৳0</span>
                        </div>
                    </div>
                </div>

                <button class="w-full bg-white/10 text-gray-500 font-bold py-4 rounded-xl text-sm transition-all cursor-not-allowed border border-white/5">
                    Requires ৳1,000 Minimum
                </button>
            </div>

            <!-- Referrals Table -->
            <div class="lg:col-span-2 bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Recent Referrals</h3>
                        <p class="text-sm text-gray-500 mt-1">Status of your recent referred users.</p>
                    </div>
                    <a href="#" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b border-gray-100">
                            <tr>
                                <th class="pb-4 text-xs font-bold text-gray-400 uppercase tracking-widest">User</th>
                                <th class="pb-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="pb-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Commission</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($referrals as $referral)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-900 font-bold text-sm">
                                                {{ substr($referral->referred->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $referral->referred->name }}</p>
                                                <p class="text-xs font-bold text-gray-500">{{ $referral->referred->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 text-center">
                                        @if($referral->status === 'converted')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Paid User</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-100">Free Trial</span>
                                        @endif
                                    </td>
                                    <td class="py-5 text-right">
                                        <span class="text-sm font-bold {{ $referral->commission_amount > 0 ? 'text-emerald-600' : 'text-gray-300' }}">
                                            ৳{{ number_format($referral->commission_amount, 0) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-20 text-center text-gray-400 font-bold text-sm opacity-50">No referrals detected yet. Start sharing your link to earn!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
