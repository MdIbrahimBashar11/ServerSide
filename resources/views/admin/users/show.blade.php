<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">{{ $user->email }}</p>
                <div class="text-xs text-gray-500 font-bold font-mono mt-2 uppercase flex items-center gap-2">
                    Role: <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 font-bold">{{ $user->role }}</span>
                    <span>|</span>
                    Status: <span class="{{ $user->status === 'active' ? 'text-emerald-700 bg-emerald-50 border-emerald-100' : 'text-red-700 bg-red-50 border-red-100' }} px-2 py-0.5 rounded border font-bold">{{ strtoupper($user->status) }}</span>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 border border-gray-200 px-5 py-3 rounded-xl transition">
                    Back to Dashboard
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-5 py-3 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-md transition active:scale-95">
                    Edit Account
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Projects & Domains Managed -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-gray-100 pb-5 mb-5 bg-gray-50/30">
                    <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider">Assigned Projects</h3>
                    <span class="text-xs bg-gray-100 text-gray-700 font-bold px-3 py-1 rounded-full border border-gray-200">{{ $user->projects->count() }} Domain(s)</span>
                </div>

                <div class="flex-1 overflow-y-auto max-h-[400px] divide-y divide-gray-100 pr-2">
                    @forelse($user->projects as $p)
                    <div class="py-4 flex justify-between items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 tracking-tight hover:text-emerald-600 transition cursor-pointer">{{ $p->name }}</h4>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $p->custom_domain }}</p>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Status: 
                                <span class="{{ $p->domain_status === 'verified' ? 'text-emerald-600' : 'text-amber-600' }}">{{ strtoupper($p->domain_status) }}</span>
                            </p>
                        </div>
                        <span class="text-xs font-bold font-mono px-2.5 py-1 bg-gray-50 text-gray-600 rounded border border-gray-200 uppercase tracking-wider">{{ $p->platform }}</span>
                    </div>
                    @empty
                    <div class="py-8 text-center">
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest italic">No associated active projects found</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Invoices & Payment Records -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-gray-100 pb-5 mb-5 bg-gray-50/30">
                    <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider">Invoices & Payment Records</h3>
                    <span class="text-xs bg-gray-100 text-gray-700 font-bold px-3 py-1 rounded-full border border-gray-200">{{ $user->invoices->count() }} Invoices</span>
                </div>

                <div class="flex-1 overflow-y-auto max-h-[400px] divide-y divide-gray-100 pr-2">
                    @forelse($user->invoices as $inv)
                    <div class="py-4 flex justify-between items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 tracking-tight hover:text-emerald-600 transition cursor-pointer">${{ number_format($inv->amount, 2) }} USD</h4>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $inv->gateway ? strtoupper($inv->gateway) : 'System Invoice' }}</p>
                            <p class="text-[10px] font-bold text-gray-400 mt-1">Transaction Ref: {{ $inv->transaction_id ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full border uppercase tracking-wider {{ $inv->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                                {{ $inv->status }}
                            </span>
                            <p class="text-[10px] text-gray-400 mt-2 font-medium">{{ $inv->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center">
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest italic">No billing records on file</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
