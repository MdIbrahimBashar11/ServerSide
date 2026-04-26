<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 border-b border-gray-200 pb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Support & Help</h1>
                <p class="text-base text-gray-600 mt-2">Speak directly with our technical support team for assistance.</p>
            </div>
            <button x-data x-on:click="$dispatch('open-modal', 'create-ticket')" class="bg-gray-900 hover:bg-black text-white px-8 py-4 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-3 active:scale-95">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Create New Ticket
            </button>
        </div>

        @if(session('status'))
            <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 font-bold text-sm flex items-center gap-4">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Tickets List -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @if($tickets->isEmpty())
                <div class="py-24 text-center flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center mb-6">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No active tickets</h3>
                    <p class="text-base text-gray-600 max-w-sm">You haven't created any support tickets yet. Click the button above to get help.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-200">
                            <tr>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Subject</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Opened On</th>
                                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition-all cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket->id) }}'">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-bold text-gray-900 tracking-tight">{{ $ticket->subject }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">ID: #{{ strtoupper(substr($ticket->id, 0, 8)) }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    @if($ticket->status === 'open')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Pending Response</span>
                                    @elseif($ticket->status === 'answered')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">Reply Received</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-500 border border-gray-200">Resolved / Closed</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-bold text-gray-600">{{ $ticket->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 decoration-2 underline-offset-4 underline">View Ticket</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Create Ticket Modal -->
        <x-modal name="create-ticket" focusable>
            <form method="post" action="{{ route('tickets.store') }}" class="p-10 bg-white">
                @csrf
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900">New Support Ticket</h2>
                    <p class="text-base text-gray-600 mt-1">Provide details about your issue for our team to review.</p>
                </div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Subject</label>
                        <input name="subject" type="text" class="block w-full border-gray-300 rounded-xl py-4 px-6 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold" placeholder="E.g. API Integration Issue" required />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Detailed Message</label>
                        <textarea name="message" rows="6" class="block w-full border-gray-300 rounded-xl py-4 px-6 text-gray-900 focus:ring-emerald-600 focus:border-emerald-600 transition font-bold leading-relaxed" placeholder="Describe the problem you're facing..." required></textarea>
                    </div>
                </div>

                <div class="mt-12 flex justify-end items-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')" class="text-sm font-bold text-gray-500 hover:text-gray-900 transition">Cancel</button>
                    <button type="submit" class="px-10 py-4 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-emerald-700 transition">Submit Ticket</button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>
