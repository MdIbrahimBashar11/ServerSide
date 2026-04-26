<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-zinc-100 leading-tight tracking-tight">
            {{ __('Super Admin Support Queue') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Quick Actions -->
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="bg-zinc-900 border border-zinc-800 text-zinc-300 px-5 py-2 rounded-xl text-sm font-bold shadow hover:bg-zinc-800 transition">Back to Main System</a>
            </div>

            <!-- Tickets Table -->
            <div class="bg-zinc-900/50 backdrop-blur-md rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-950/50 border-b border-zinc-800 text-xs uppercase font-bold text-zinc-500 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tenant</th>
                            <th class="px-6 py-4">Subject Requirement</th>
                            <th class="px-6 py-4">Created On</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800 font-medium">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-6 py-4">
                                @if($ticket->status === 'open')
                                    <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 inline-flex"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse block"></span> Requires Action</span>
                                @elseif($ticket->status === 'answered')
                                    <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest">Waiting Tenant</span>
                                @else
                                    <span class="bg-zinc-800 text-zinc-500 border border-zinc-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest">Closed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-white">{{ $ticket->user->name }}</td>
                            <td class="px-6 py-4 text-zinc-300 font-bold">{{ $ticket->subject }}</td>
                            <td class="px-6 py-4 text-zinc-500">{{ $ticket->created_at->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-indigo-400 font-bold text-sm hover:underline">Address Case →</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
