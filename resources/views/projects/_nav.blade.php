<div x-data="{}" class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
    <div class="flex items-center gap-6">
        <!-- Project Switcher -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="bg-white border border-gray-200 text-gray-900 px-6 py-3 rounded-xl flex items-center gap-4 shadow-sm hover:border-emerald-500 transition active:scale-95">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2-2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Current Project</p>
                    <span class="font-bold text-gray-900 text-base leading-none">{{ $project->name }}</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 ml-2" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            
            <!-- Dropdown -->
            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden" style="display: none;">
                <div class="p-2">
                    <p class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 mb-2">My Projects</p>
                    <div class="max-h-60 overflow-y-auto">
                        @foreach(Auth::user()->projects as $p)
                        <a href="{{ route('projects.show', $p->id) }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold {{ $p->id === $project->id ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }}">
                            <div class="w-1.5 h-1.5 rounded-full {{ $p->id === $project->id ? 'bg-emerald-600' : 'bg-gray-300' }}"></div>
                            {{ $p->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Links -->
        <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-xl border border-gray-200">
            <a href="{{ route('projects.show', $project->id) }}" class="px-6 py-2.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('projects.show') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                Analytics
            </a>
            <a href="{{ route('projects.events', $project->id) }}" class="px-6 py-2.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('projects.events') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                Events
            </a>
            <a href="{{ route('projects.edit', $project->id) }}" class="px-6 py-2.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('projects.edit') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                Configuration
            </a>
            <a href="{{ route('projects.setup', $project->id) }}" class="px-6 py-2.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('projects.setup') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                Setup Kit
            </a>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4">
        <button @click="$dispatch('open-modal', 'event-debugger')" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gray-900 text-white text-xs font-bold shadow-md hover:bg-black transition active:scale-95 leading-none">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            Live Monitor
        </button>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center w-12 h-12 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-gray-900 transition shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
    </div>
</div>

@include('projects._debugger_modal')
