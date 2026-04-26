<x-app-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-12">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Finalize Deployment</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-3">Sector: Billing — Protocol: Payment Gateways</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                <!-- Left: Plan Summary -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 opacity-10 group-hover:scale-110 transition-transform">
                            <svg class="w-24 h-24 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-4">Selected Tier</h4>
                        <h3 class="text-2xl font-black mb-2">{{ $plan->name }}</h3>
                        <p class="text-4xl font-black tracking-tighter mb-8">৳{{ number_format($plan->price) }} <span class="text-xs opacity-40">/ MO</span></p>
                        
                        <div class="space-y-3 pt-6 border-t border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wide">{{ number_format($plan->event_limit) }} Events</span>
                            </div>
                            <div class="flex items-center gap-3 opacity-50">
                                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wide">Infrastructure Routing</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border border-dashed border-gray-200 rounded-3xl">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2 italic">Security Advisory:</p>
                        <p class="text-[10px] text-gray-400 leading-relaxed font-bold">Payments are handled through encrypted tunnels. We never store raw card numbers on our relay nodes.</p>
                    </div>
                </div>

                <!-- Right: Gateway Selection -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl p-10 sm:p-14">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                            <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                            Select Gateway Method
                        </h3>

                        <form action="{{ route('billing.process') }}" method="POST" x-data="{ gateway: 'stripe' }" class="space-y-6">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="gateway" :value="gateway">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Stripe -->
                                <div @click="gateway = 'stripe'" :class="gateway === 'stripe' ? 'border-emerald-600 bg-emerald-50/20' : 'border-gray-50 bg-gray-50/30 hover:bg-gray-50'" class="p-6 rounded-[2rem] border-2 transition-all cursor-pointer group flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center transition-all" :class="gateway === 'stripe' && 'shadow-xl shadow-emerald-500/10 border-emerald-500'">
                                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M13.911 8.612c0-.574.498-.822 1.362-.822 1.173 0 2.226.43 2.226.43l.404-2.023s-1.123-.518-2.673-.518c-2.31 0-3.69 1.15-3.69 3.033 0 2.81 3.864 2.378 3.864 3.601 0 .61-.536.85-1.464.85-1.328 0-2.585-.561-2.585-.561l-.427 2.13s1.25.684 3.064.684c2.404-.012 3.737-1.161 3.737-3.056-.001-2.92-3.818-2.457-3.818-3.649z"/></svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Gate: 01</p>
                                        <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Stripe / Cards</p>
                                    </div>
                                </div>

                                <!-- PayPal -->
                                <div @click="gateway = 'paypal'" :class="gateway === 'paypal' ? 'border-blue-600 bg-blue-50/20' : 'border-gray-50 bg-gray-50/30 hover:bg-gray-50'" class="p-6 rounded-[2rem] border-2 transition-all cursor-pointer group flex items-center gap-5 opacity-60">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center transition-all" :class="gateway === 'paypal' && 'shadow-xl shadow-blue-500/10 border-blue-500'">
                                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M7.076 21.337H2.47a.641.641 0 01-.633-.74L4.944 3.722a1.06 1.06 0 011.047-.89h7.49c3.38 0 5.7.688 6.9 2.053 1.25 1.428 1.188 3.521.562 5.775a.91.91 0 01-.06.18c-.813 4.14-3.562 6.44-7.562 6.44h-2.18c-.466 0-.877.303-.996.75l-1.047 4.147a1.01 1.01 0 01-.39 1.16zm9.324-11.838l.123-.19.03-.05c.5-1.562.438-2.625-.188-3.375C15.82 5.053 14.508 4.63 12.82 4.63H6.47a.64.64 0 00-.633.535l-.123.5-.04.144L3.18 19.337h2.812l1.047-4.147a.64.64 0 01.633-.535h3.06c4 0 6.688-2.312 7.688-5.156z"/></svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Gate: 02</p>
                                        <p class="text-sm font-black text-gray-900 uppercase tracking-tight">PayPal Global</p>
                                    </div>
                                </div>

                                <!-- bKash -->
                                <div @click="gateway = 'bkash'" :class="gateway === 'bkash' ? 'border-pink-600 bg-pink-50/20' : 'border-gray-50 bg-gray-50/30 hover:bg-gray-50'" class="p-6 rounded-[2rem] border-2 transition-all cursor-pointer group flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center transition-all overflow-hidden" :class="gateway === 'bkash' && 'shadow-xl shadow-pink-500/10 border-pink-500'">
                                        <img src="https://www.logo.wine/a/logo/BKash/BKash-Icon-Logo.wine.svg" class="w-10 h-10 object-contain" alt="bKash">
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Sector: BD</p>
                                        <p class="text-sm font-black text-gray-900 uppercase tracking-tight">bKash (MFS)</p>
                                    </div>
                                </div>

                                <!-- SSLCommerz -->
                                <div @click="gateway = 'sslcommerz'" :class="gateway === 'sslcommerz' ? 'border-emerald-600 bg-emerald-50/20' : 'border-gray-50 bg-gray-50/30 hover:bg-gray-50'" class="p-6 rounded-[2rem] border-2 transition-all cursor-pointer group flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center transition-all overflow-hidden" :class="gateway === 'sslcommerz' && 'shadow-xl shadow-emerald-500/10 border-emerald-500'">
                                        <span class="text-xs font-black text-emerald-600">SSL</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Sector: Multi</p>
                                        <p class="text-sm font-black text-gray-900 uppercase tracking-tight">SSLCommerz BD</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-10">
                                <button class="w-full py-5 bg-gray-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.4em] shadow-2xl transition hover:bg-black active:scale-[0.98] flex items-center justify-center gap-3">
                                    Initiate Process
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="flex justify-between items-center px-4">
                        <div class="flex gap-4">
                             <img src="https://www.svgrepo.com/show/508695/visa.svg" class="h-4 opacity-30 italic" alt="Visa">
                             <img src="https://www.svgrepo.com/show/508401/mastercard.svg" class="h-4 opacity-30 italic" alt="Master">
                             <img src="https://www.svgrepo.com/show/508442/google-pay.svg" class="h-4 opacity-30 italic" alt="GPay">
                        </div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest italic flex items-center gap-2">
                             <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                             Secure Pipeline Connection
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
