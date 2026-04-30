@extends('layouts.landing')

@section('title', 'Eventrix — Professional Server-Side Tracking')

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
@endsection
