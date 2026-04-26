<?php
namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketMessage;

class TicketController extends Controller
{
    public function index() {
        $tickets = auth()->user()->tickets()->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function store(Request $request) {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $ticket = auth()->user()->tickets()->create([
            'subject' => $request->subject,
            'status' => 'open'
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return back()->with('status', 'Support request initialized. A representative will review it shortly.');
    }

    public function show(Ticket $ticket) {
        if($ticket->user_id !== auth()->id()) abort(403);
        $ticket->load('messages.user');
        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket) {
        if($ticket->user_id !== auth()->id()) abort(403);
        $request->validate(['message' => 'required|string']);
        
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        // Automatically set ticket to open if closed upon tenant reply
        if($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back();
    }
}
