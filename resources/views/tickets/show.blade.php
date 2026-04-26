<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-4">
            <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-900 font-bold text-sm flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Support Tickets
            </a>
            <div class="flex items-center gap-3">
                @if($ticket->status === 'open')
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        Awaiting Review
                    </span>
                @elseif($ticket->status === 'answered')
                    <span class="bg-blue-50 text-blue-700 border border-blue-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">
                        Reply Received
                    </span>
                @else
                    <span class="bg-gray-100 text-gray-600 border border-gray-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest text-center">
                        Resolved & Closed
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Subject</p>
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $ticket->subject }}</h1>
                <p class="text-xs font-bold text-gray-400 mt-4 uppercase tracking-widest">Ticket ID: #{{ strtoupper(substr($ticket->id, 0, 8)) }}</p>
            </div>

            <div class="p-8 space-y-8">
                @foreach($ticket->messages as $msg)
                <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%]">
                        <div class="flex items-center gap-3 mb-2 {{ $msg->user_id === auth()->id() ? 'justify-end' : '' }}">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ $msg->user_id === auth()->id() ? 'You' : 'System Engineer' }}
                            </span>
                            <span class="text-[10px] text-gray-300 font-bold uppercase">{{ $msg->created_at->format('M d, H:i') }}</span>
                        </div>
                        <div class="p-6 rounded-2xl {{ $msg->user_id === auth()->id() ? 'bg-emerald-600 text-white shadow-md' : 'bg-gray-100 text-gray-900' }}">
                            <p class="whitespace-pre-wrap text-sm font-bold leading-relaxed">{{ $msg->message }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($ticket->status !== 'closed')
            <div class="p-8 border-t border-gray-100 bg-gray-50/30">
                <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <textarea name="message" rows="5" class="block w-full border-gray-200 rounded-xl py-4 px-6 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold text-sm leading-relaxed mb-6" placeholder="Write your reply..." required></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gray-900 hover:bg-black text-white px-10 py-4 rounded-xl font-bold text-sm shadow-md transition-all active:scale-95 flex items-center gap-3">
                            Send Reply
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
