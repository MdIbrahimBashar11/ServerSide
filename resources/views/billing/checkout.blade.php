<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Activate Your Plan</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Review the chosen subscription plan and activate your account tracker.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 border border-gray-200 px-5 py-3 rounded-xl transition">
                Back to Dashboard
            </a>
        </div>

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-900 text-sm font-bold shadow-sm">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Plan Summary Card -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full">Selected Tier</span>
                    <h2 class="text-2xl font-black text-gray-900 mt-4 leading-tight">{{ $plan->name }}</h2>
                    <p class="text-4xl font-black text-gray-900 mt-4">৳{{ number_format($plan->price, 0) }}<span class="text-sm font-bold text-gray-400"> / month</span></p>
                    
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-bold text-gray-700">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                {{ number_format($plan->event_limit) }} Events capacity
                            </li>
                            <li class="flex items-center gap-3 text-sm font-bold text-gray-700">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                High-speed infrastructure routing
                            </li>
                        </ul>
                    </div>
                </div>
            </div>            <!-- Payment Or Activation Action Card -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm flex flex-col justify-center">
                <form action="{{ route('billing.process') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}" />



                    @if($plan->price == 0)
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-2">Process Free Trial Activation</h3>
                            <p class="text-sm font-medium text-gray-500 mb-6">Since this plan is free, no payment gateway selection is required.</p>
                        </div>
                        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold uppercase tracking-widest shadow-md transition-all active:scale-95">
                            Process Activation
                        </button>
                    @else
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-2">Choose Payment Gateway</h3>
                            <p class="text-sm font-medium text-gray-500 mb-4">Select the payment method you wish to use.</p>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="flex items-center justify-between border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-emerald-500 hover:bg-emerald-50/20 transition">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="gateway" value="stripe" checked class="text-emerald-600 focus:ring-emerald-500 border-gray-300" />
                                    <span class="font-bold text-sm text-gray-800">Stripe Card Payment</span>
                                </span>
                            </label>

                            <label class="flex items-center justify-between border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-emerald-500 hover:bg-emerald-50/20 transition">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="gateway" value="bkash" class="text-emerald-600 focus:ring-emerald-500 border-gray-300" />
                                    <span class="font-bold text-sm text-gray-800">bKash</span>
                                </span>
                            </label>

                            <label class="flex items-center justify-between border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-emerald-500 hover:bg-emerald-50/20 transition">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="gateway" value="sslcommerz" class="text-emerald-600 focus:ring-emerald-500 border-gray-300" />
                                    <span class="font-bold text-sm text-gray-800">SSLCommerz</span>
                                </span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-4 mt-6 bg-gray-900 hover:bg-black text-white rounded-xl text-sm font-bold uppercase tracking-widest shadow-md transition-all active:scale-95">
                            Proceed to Payment
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
