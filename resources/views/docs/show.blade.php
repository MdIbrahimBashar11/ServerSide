@extends('layouts.landing')

@section('title', ($page === 'introduction' ? 'Documentation' : ucwords(str_replace('-', ' ', $page))) . ' — Eventrix')

@push('styles')
    <style>
        /* Prose Overrides for Documentation */
        .prose h1 { color: #111827; font-weight: 800; font-family: 'Outfit', sans-serif; letter-spacing: -0.025em; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; margin-bottom: 2rem; }
        .prose h2 { color: #111827; font-weight: 700; margin-top: 3rem; margin-bottom: 1rem; font-family: 'Outfit', sans-serif; }
        .prose h3 { color: #111827; font-weight: 600; margin-top: 2rem; }
        .prose p { color: #4b5563; line-height: 1.8; margin-bottom: 1.5rem; font-size: 1.05rem; }
        .prose a { color: #059669; font-weight: 600; text-decoration: underline; text-underline-offset: 4px; }
        .prose a:hover { color: #047857; }
        
        .prose code { 
            background-color: #f3f4f6; 
            color: #059669; 
            padding: 0.2rem 0.4rem; 
            border-radius: 0.4rem; 
            font-size: 0.9em;
            font-weight: 600;
        }
        .prose code::before, .prose code::after { content: ""; }
        
        .prose pre {
            background-color: #0f172a !important; 
            border: 1px solid #1e293b; 
            border-radius: 1rem;
            padding: 1.5rem;
            overflow-x: auto;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }
        .prose pre code { background-color: transparent; color: #e2e8f0; padding: 0; font-weight: 400; font-size: 0.9rem; }
        
        .prose ul { list-style-type: none; padding-left: 0; margin-bottom: 1.5rem; }
        .prose li { position: relative; padding-left: 1.75rem; margin-bottom: 0.75rem; color: #4b5563; }
        .prose li::before { 
            content: "→"; 
            position: absolute; 
            left: 0; 
            color: #10b981; 
            font-weight: 900;
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
<br><br>
    <div class="pt-28 flex-1 flex w-full max-w-screen-2xl mx-auto">
        <!-- Sidebar Navigation -->
        <aside class="w-72 hidden lg:block sticky top-[100px] h-[calc(100vh-100px)] border-r border-gray-50 overflow-y-auto py-10 px-6">
            @foreach($navigation as $section => $links)
                <div class="mb-10">
                    <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">{{ $section }}</h4>
                    <ul class="space-y-1">
                        @foreach($links as $slug => $title)
                            <li>
                                <a href="{{ route('docs', $slug) }}" 
                                   class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $page === $slug ? 'bg-emerald-50 text-emerald-700 font-bold shadow-sm shadow-emerald-600/5' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $page === $slug ? 'bg-emerald-500' : 'bg-transparent group-hover:bg-gray-300' }} transition-colors"></span>
                                    {{ $title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 py-12 lg:px-16 px-6 overflow-hidden">
            <div class="max-w-3xl fade-in-up">
                <!-- Breadcrumbs -->
                <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest mb-8" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('docs') }}" class="hover:text-emerald-600 transition">Docs</a></li>
                        <li class="flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-gray-900">{{ ucwords(str_replace('-', ' ', $page)) }}</span>
                        </li>
                    </ol>
                </nav>

                <article class="prose max-w-none">
                    {!! $content !!}
                </article>

                <div class="mt-20 pt-10 border-t border-gray-100 flex justify-between items-center text-sm font-medium text-gray-500">
                    <span>Last updated: April 2026</span>
                    <a href="mailto:support@eventrix.app" class="hover:text-emerald-600 transition">Report an issue</a>
                </div>
            </div>
        </main>

        <!-- Right Sidebar (TOC placeholder) -->
        <aside class="w-64 hidden xl:block sticky top-[100px] h-[calc(100vh-100px)] py-10 px-6">
            <div class="bg-emerald-50/50 rounded-2xl p-6 border border-emerald-100/50">
                <h5 class="text-emerald-800 font-bold text-sm mb-3">Pro Tip</h5>
                <p class="text-emerald-700/80 text-xs leading-relaxed">
                    Always use the <strong>Test Event Tool</strong> in the Facebook Events Manager when verifying your first-party CAPI setup.
                </p>
                <a href="#" class="inline-block mt-4 text-[10px] font-black text-emerald-800 uppercase tracking-widest hover:underline">Learn more</a>
            </div>
        </aside>
    </div>

    <!-- Mobile Navigation Toggle (Simple implementation) -->
    <div class="lg:hidden fixed bottom-6 right-6 z-50">
        <button onclick="document.getElementById('mobile-docs-nav').classList.toggle('hidden')" class="w-14 h-14 bg-emerald-600 text-white rounded-full shadow-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </div>
    
    <div id="mobile-docs-nav" class="hidden fixed inset-0 z-[60] bg-white p-8 overflow-y-auto">
        <button onclick="document.getElementById('mobile-docs-nav').classList.toggle('hidden')" class="absolute top-6 right-6 text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        @foreach($navigation as $section => $links)
            <div class="mb-10">
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">{{ $section }}</h4>
                <ul class="space-y-4">
                    @foreach($links as $slug => $title)
                        <li>
                            <a href="{{ route('docs', $slug) }}" class="text-xl font-bold {{ $page === $slug ? 'text-emerald-600' : 'text-gray-900' }}">
                                {{ $title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
@endsection
