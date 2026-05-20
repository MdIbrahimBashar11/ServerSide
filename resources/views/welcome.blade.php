@extends('layouts.landing')

@section('title', 'RecordSync — Professional Server-Side Tracking')

@section('content')
    <!-- Hero Section -->
    <section class="pt-40 pb-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest mb-8">Professional Server-Side Tracking</span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 leading-[1.1] tracking-tight mb-8">
                Bypass restrictions. <br />
                <span class="text-emerald-600">Reclaim your data.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-gray-600 font-medium mb-12">
                The most reliable server-to-server tracking infrastructure. Send event data directly to Meta CAPI, GA4, and TikTok without relying on browser pixels.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-20">
                <a href="{{ route('register') }}" class="px-10 py-4 rounded-xl bg-emerald-600 text-white font-bold text-lg hover:bg-emerald-700 hover:-translate-y-1 transition shadow-lg shadow-emerald-600/20">Create Free Account</a>
                <a href="#pricing" class="px-10 py-4 rounded-xl border border-gray-300 text-gray-700 font-bold text-lg hover:bg-gray-50 transition">View Plans</a>
            </div>

            <!-- Dashboard Preview -->
            <div class="relative max-w-5xl mx-auto">
                <div class="bg-white rounded-3xl p-4 shadow-2xl border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=2000&q=80" alt="Dashboard Preview" class="rounded-2xl w-full h-[500px] object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24" id="features">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Everything you need to scale</h2>
                <p class="text-gray-600 text-lg">Powerful features built for modern digital marketers.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Zero Data Loss</h3>
                    <p class="text-gray-600 leading-relaxed">Bypass ad-blockers and iOS restrictions by sending data server-to-server with 100% reliability.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Secure & Private</h3>
                    <p class="text-gray-600 leading-relaxed">Your data is never shared. Secure tracking IDs and encrypted transmission ensure your business remains compliant.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Multi-Channel</h3>
                    <p class="text-gray-600 leading-relaxed">Support for Meta, Google, TikTok, and custom webhooks. All from a single event stream.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-24 bg-gray-50 uppercase" id="pricing">
        <div class="max-w-7xl mx-auto px-6 font-bold">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-outfit tracking-tighter">Simple transparent pricing</h2>
                <p class="text-gray-600">Choose the plan that fits your business scale.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($packages as $package)
                    <div class="bg-white p-10 rounded-2xl border border-gray-200 flex flex-col shadow-sm transition-all hover:border-emerald-600 group">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $package->name }}</h3>
                        <div class="flex items-baseline gap-1 mb-8">
                            <span class="text-4xl font-bold text-gray-900">৳{{ number_format($package->price, 0) }}</span>
                            <span class="text-gray-500 text-xs">/month</span>
                        </div>

                        <a href="{{ route('register') }}" class="w-full py-3 rounded-lg text-center text-sm font-bold transition {{ $loop->iteration === 2 ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }} mb-10">
                            Get Started
                        </a>

                        <ul class="space-y-4 flex-1">
                            <li class="flex items-center gap-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                {{ number_format($package->event_limit) }} Events / Month
                            </li>
                            @if($package->features)
                                @foreach($package->features as $feature)
                                    <li class="flex items-center gap-3 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                @empty
                    <div class="col-span-3 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-sm opacity-50 italic">Pricing options coming soon.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 bg-white" id="how-it-works">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4">Process Workflow</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-gray-600 text-lg">Set up server-side tracking in minutes with our simple 3-step process.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    <div>
                        <span class="text-4xl font-extrabold text-emerald-600 mb-4 block">01</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Create Account & Project</h3>
                        <p class="text-gray-600 leading-relaxed">Sign up, select your tier, and create your first tracking project within our dashboard.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    <div>
                        <span class="text-4xl font-extrabold text-emerald-600 mb-4 block">02</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Integrate Tracking SDK</h3>
                        <p class="text-gray-600 leading-relaxed">Add our direct tracking snippet or call our webhook endpoint on any conversion point on your server.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    <div>
                        <span class="text-4xl font-extrabold text-emerald-600 mb-4 block">03</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Monitor Data Flow</h3>
                        <p class="text-gray-600 leading-relaxed">Bypass all client-side blocks. See events arriving real-time and stream them straight to ad platforms.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-24 bg-gray-50" id="benefits">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4">Why RecordSync</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Maximize Your ROI</h2>
                <p class="text-gray-600 text-lg">Outperform your competitors by fixing broken attribution models.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-white p-10 rounded-2xl border border-gray-200 flex flex-col justify-between shadow-sm hover:border-emerald-500 transition">
                    <div>
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Optimize Marketing Spend</h3>
                        <p class="text-gray-600 leading-relaxed">When data loss occurs, ad platforms cannot optimize your campaigns correctly. Server-side tracking unlocks higher match rates and lowers your cost per acquisition.</p>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-2xl border border-gray-200 flex flex-col justify-between shadow-sm hover:border-emerald-500 transition">
                    <div>
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Future-Proof Architecture</h3>
                        <p class="text-gray-600 leading-relaxed">Browser cookies are getting phased out. Server-to-server ensures you have complete ownership and security of your first-party customer signals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-white" id="faq">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4">Support Center</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-600 text-lg">Everything you need to know about setting up tracking.</p>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 border border-gray-200 p-8 rounded-xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">What is server-side tracking?</h3>
                    <p class="text-gray-600 leading-relaxed">Instead of relying on browser scripts that can be blocked, your server sends purchase or lead data directly to advertising networks using highly secure server APIs.</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 p-8 rounded-xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">How long does integration take?</h3>
                    <p class="text-gray-600 leading-relaxed">It takes less than 10 minutes to register, set up a project, and install the provided SDK or Webhook snippet into your website code.</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 p-8 rounded-xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">What happens if I exceed my event limit?</h3>
                    <p class="text-gray-600 leading-relaxed">Your data flow will pause if the monthly limit is exceeded, but you can upgrade your plan at any time within the billing dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-24 bg-gray-50 border-t border-gray-100" id="contact">
        <div class="max-w-xl mx-auto px-6">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4">Contact Support</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Get in Touch</h2>
                <p class="text-gray-600 text-lg">Send a message directly via WhatsApp</p>
            </div>

            <form onsubmit="handleContact(event)" class="bg-white p-8 md:p-10 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" id="contact-name" placeholder="John Doe" class="block w-full border-gray-200 rounded-xl py-4 px-5 text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 font-bold transition" required />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Message</label>
                    <textarea id="contact-message" rows="4" placeholder="Hello! I need help with..." class="block w-full border-gray-200 rounded-xl py-4 px-5 text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 font-bold transition" required></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold uppercase tracking-widest shadow-md transition hover:-translate-y-0.5">
                    Submit Message
                </button>
            </form>
        </div>

        <script>
            function handleContact(e) {
                e.preventDefault();
                const name = document.getElementById('contact-name').value;
                const message = document.getElementById('contact-message').value;
                const text = `Hello RecordSync! My name is ${encodeURIComponent(name)}. ${encodeURIComponent(message)}`;
                window.open(`https://wa.me/8801713728112?text=${text}`, '_blank');
            }
        </script>
    </section>
@endsection
