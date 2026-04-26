<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentation - ServerTrack</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .prose pre {
            background-color: #18181b !important; 
            border: 1px solid #27272a; 
            border-radius: 0.75rem;
            padding: 1.5rem;
            overflow-x: auto;
        }
        .prose code { color: #818cf8; font-weight: 600; }
        .prose pre code { color: #e4e4e7; font-weight: 400; }
        .prose h1, .prose h2, .prose h3 { color: #f4f4f5; font-weight: 700; margin-top: 2em; margin-bottom: 1em; }
        .prose p { color: #a1a1aa; line-height: 1.75; margin-bottom: 1.25em; }
        .prose ul { color: #a1a1aa; list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.25em; }
        .prose ol { color: #a1a1aa; list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1.25em; }
        .prose a { color: #818cf8; text-decoration: none; }
        .prose a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-zinc-950 text-white font-sans antialiased flex flex-col h-screen overflow-hidden">
    
    <!-- Docs Navbar -->
    <nav class="flex-shrink-0 border-b border-zinc-800 bg-zinc-950/80 backdrop-blur z-20">
        <div class="px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold">ST</div>
                <span class="font-semibold text-lg">Docs</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-400 hover:text-white transition">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 flex-shrink-0 border-r border-zinc-800 bg-zinc-900/30 overflow-y-auto hidden md:block">
            <div class="p-6">
                @foreach($navigation as $section => $links)
                    <div class="mb-8">
                        <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mb-3">{{ $section }}</h4>
                        <ul class="space-y-2">
                            @foreach($links as $slug => $title)
                                <li>
                                    <a href="{{ route('docs', $slug) }}" 
                                       class="text-sm block py-1.5 px-3 -mx-3 rounded-md transition {{ $page === $slug ? 'text-indigo-400 bg-indigo-500/10 font-medium' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-zinc-950 p-6 md:p-12 lg:px-24">
            <div class="max-w-3xl mx-auto prose prose-invert">
                {!! $content !!}
            </div>
        </main>
    </div>

</body>
</html>
