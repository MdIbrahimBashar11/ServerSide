<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-zinc-100 leading-tight tracking-tight">
            {{ __('Address Support Case: ' . $ticket->id) }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('admin.tickets.index') }}" class="text-zinc-400 hover:text-white font-bold text-sm flex items-center gap-1 transition">
                    ← Back to Queue
                </a>
            </div>

            <div class="bg-zinc-900/50 backdrop-blur-md rounded-2xl border border-zinc-800 shadow-2xl p-8">
                <div class="border-b border-zinc-800 pb-6 mb-6">
                    <h1 class="text-2xl font-bold text-white">{{ $ticket->subject }}</h1>
                    <p class="text-sm font-medium text-zinc-500 mt-2">Opened by {{ $ticket->user->name }} ({{ $ticket->user->email }}) • {{ $ticket->created_at->format('M d, Y') }}</p>
                </div>

                <div class="space-y-6">
                    @foreach($ticket->messages as $msg)
                    <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] rounded-2xl p-5 {{ $msg->user_id === auth()->id() ? 'bg-indigo-600 text-white rounded-tr-sm shadow-lg shadow-indigo-600/20' : 'bg-zinc-800 text-zinc-300 rounded-tl-sm' }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold uppercase tracking-widest {{ $msg->user_id === auth()->id() ? 'text-indigo-200' : 'text-zinc-500' }}">{{ $msg->user_id === auth()->id() ? 'You (Admin)' : $ticket->user->name }}</span>
                                <span class="text-[10px] {{ $msg->user_id === auth()->id() ? 'text-indigo-200' : 'text-zinc-600' }}">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="whitespace-pre-wrap font-medium text-sm leading-relaxed">{{ $msg->message }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-zinc-800">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="4" class="block w-full bg-zinc-950 border border-zinc-800 text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition placeholder-zinc-600 mb-4" placeholder="Draft administrative response..."></textarea>
                        
                        <div class="flex justify-end gap-3">
                            <button name="action" value="close" type="submit" class="bg-zinc-800 hover:bg-zinc-700 text-white px-6 py-2.5 rounded-lg font-bold transition">Close Ticket</button>
                            <button name="action" value="reply" type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg shadow-indigo-600/20 transition flex items-center gap-2">
                                Send Response & Flag "Answered"
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
