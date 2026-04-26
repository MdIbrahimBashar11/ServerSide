@props(['maxWidth'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ServerTrack') }} — Secure Gateway</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafe; }
            .auth-card { 
                max-width: {{ $maxWidth === 'max-w-4xl' ? '900px' : ($maxWidth === 'max-w-2xl' ? '672px' : '480px') }}; 
                width: 95%;
            }
        </style>
    </head>
<body class="antialiased text-gray-900 bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center p-8 sm:p-20">
            
            <!-- Logo Section -->
            <div class="mb-10 text-center">
                <div class="inline-flex w-16 h-16 bg-gray-900 rounded-2xl items-center justify-center shadow-xl shadow-gray-900/10 mb-6">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 uppercase tracking-tight">Core Relay <span class="text-emerald-600 italic">v2.0</span></h1>
            </div>

            <!-- Main Auth Card -->
            <div class="auth-card bg-white border border-gray-200 rounded-[2.5rem] shadow-2xl shadow-gray-200/40 p-8 sm:p-14 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-emerald-500"></div>
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">&copy; {{ date('Y') }} Infrastructure Edge Node</p>
                <div class="flex gap-6 mt-6 justify-center">
                    <a href="#" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-emerald-600 transition">Confidentiality</a>
                    <a href="#" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-emerald-600 transition">SLA Protocol</a>
                </div>
            </div>
        </div>
    </body>
</html>
