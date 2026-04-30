<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Eventrix — Professional Server-Side Tracking')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Premium Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&family=Outfit:wght@400..900&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif !important; 
            background-color: #ffffff !important;
            color: #111827;
        }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        
        /* Smooth fade-in animation for content */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased selection:bg-emerald-100 selection:text-emerald-900 overflow-x-hidden">

    <!-- 
        NAVIGATION COMPONENT
        --------------------
        - Fixed positioning with backdrop blur for a premium glassmorphism effect.
        - Responsive design: Desktop links hidden on mobile.
        - Auth-aware: Dynamic 'Dashboard' vs 'Get Started' buttons.
    -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-600/20 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="text-xl font-bold text-gray-900 tracking-tight">Eventrix</span>
            </a>
            
            <!-- Main Navigation Links -->
            <div class="hidden md:flex items-center gap-8 text-sm font-bold text-gray-600">
                <a href="/#features" class="hover:text-emerald-600 transition">Features</a>
                <a href="/#pricing" class="hover:text-emerald-600 transition">Pricing</a>
                <a href="{{ route('login') }}" class="hover:text-emerald-600 transition">Login</a>
                <a href="{{ route('docs') }}" class="text-emerald-600 hover:text-emerald-700 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Documentation
                </a>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-lg bg-gray-900 text-white font-bold text-sm hover:bg-black transition shadow-lg shadow-gray-900/10">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-lg bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="fade-in">
        @yield('content')
    </main>

    <!-- 
        FOOTER COMPONENT
        ---------------
        - Clean, minimal design.
        - Dynamic copyright year.
    -->
    <footer class="py-12 border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-emerald-600 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="text-lg font-bold text-gray-900 tracking-tight">Eventrix</span>
            </div>
            <div class="flex flex-col md:items-end gap-2">
                <p class="text-gray-400 text-sm font-medium">&copy; {{ date('Y') }} Eventrix Tracking Solutions. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-xs font-bold text-gray-400 hover:text-emerald-600 transition uppercase tracking-widest">Privacy</a>
                    <a href="#" class="text-xs font-bold text-gray-400 hover:text-emerald-600 transition uppercase tracking-widest">Terms</a>
                    <a href="{{ route('docs') }}" class="text-xs font-bold text-gray-400 hover:text-emerald-600 transition uppercase tracking-widest">Docs</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
