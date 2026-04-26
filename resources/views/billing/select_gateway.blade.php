<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Checkout — Eventrix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-active { background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.4); }
    </style>
</head>
<body class="h-full antialiased bg-[#020617] text-white overflow-x-hidden" x-data="{ selectedGateway: '{{ $gateways->first()->key ?? '' }}' }">
    
    <div class="min-h-full flex flex-col justify-center py-20 px-6 lg:px-8 relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,_rgba(16,185,129,0.1)_0%,_rgba(2,6,23,0)_70%)] pointer-events-none"></div>

        <div class="max-w-xl mx-auto w-full relative">
            <div class="text-center mb-12">
                <a href="/" class="inline-flex items-center gap-3 mb-8 group">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:rotate-12 transition">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </a>
                <h3 class="text-4xl font-black font-outfit tracking-tight mb-2">COMPLETE <br /> INITIALIZATION</h3>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Select your preferred payment gateway.</p>
            </div>

            <div class="glass rounded-[2.5rem] p-8 shadow-2xl border-white/5 mb-8">
                <div class="flex justify-between items-center mb-8 pb-8 border-b border-white/5">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Invoice ID</p>
                        <p class="text-sm font-bold text-white">#INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Grand Total</p>
                        <p class="text-3xl font-black font-outfit text-emerald-400 tracking-tighter">৳{{ number_format($invoice->amount, 0) }}</p>
                    </div>
                </div>

                <form action="{{ route('billing.invoice.checkout', $invoice) }}" method="POST">
                    @csrf
                    <input type="hidden" name="gateway" :value="selectedGateway">

                    <div class="space-y-4 mb-10">
                        @forelse($gateways as $gateway)
                            <div @click="selectedGateway = '{{ $gateway->key }}'"
                                 :class="selectedGateway === '{{ $gateway->key }}' ? 'glass-active border-emerald-500/50' : 'hover:bg-white/5'"
                                 class="glass rounded-2xl p-6 cursor-pointer transition-all flex justify-between items-center group relative overflow-hidden">
                                
                                <div class="flex items-center gap-4 relative z-10">
                                    <div class="w-12 h-12 glass rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                                        @if($gateway->key === 'stripe')
                                            <svg class="w-6 h-6 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M13.911 8.012c-1.033 0-1.748.498-1.748 1.408 0 .684.582 1.055 1.488 1.442l.859.356c1.378.583 1.83 1.353 1.83 2.14 0 1.583-1.42 2.529-2.782 2.529-.982 0-1.87-.272-2.585-.644l.115-1.724c.75.466 1.637.751 2.308.751.787 0 1.25-.336 1.25-.867 0-.583-.491-.841-1.391-1.216l-.808-.344c-1.124-.479-1.688-1.151-1.688-2.14 0-1.353 1.15-2.348 2.511-2.348.88 0 1.625.22 2.232.557l-.147 1.625c-.534-.336-1.15-.525-1.547-.525zM12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path></svg>
                                        @elseif($gateway->key === 'bkash')
                                            <span class="text-rose-500 font-black text-xs">bKash</span>
                                        @else
                                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-black uppercase tracking-tight text-white">{{ $gateway->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Connect Securely</p>
                                    </div>
                                </div>

                                <div x-show="selectedGateway === '{{ $gateway->key }}'" class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20 relative z-10">
                                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        @empty
                            <div class="glass rounded-2xl p-8 text-center">
                                <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">No payment gateways are currently active.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($gateways->count() > 0)
                        <button type="submit" class="w-full py-5 rounded-2xl bg-emerald-500 text-slate-950 font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-emerald-500/20 hover:bg-emerald-400 transition active:scale-95">
                            PAY NOW & INITIALIZE &rarr;
                        </button>
                    @endif
                </form>
            </div>

            <p class="text-center text-[10px] text-slate-600 font-black uppercase tracking-[0.3em]">
                Secure Transaction &bull; 256-bit Encryption
            </p>
        </div>
    </div>

</body>
</html>
