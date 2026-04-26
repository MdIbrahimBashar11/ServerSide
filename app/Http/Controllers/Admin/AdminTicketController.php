<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class AdminTicketController extends Controller
{
    public function index() {
        $tickets = Ticket::with('user')->latest()->get();
        return view('admin.tickets', compact('tickets'));
    }

    public function show(Ticket $ticket) {
        $ticket->load('messages.user', 'user');
        return view('admin.ticket_show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket) {
        $request->validate([
            'message' => 'required|string',
            'action' => 'required|in:reply,close'
        ]);

        if($request->message) {
            $ticket->messages()->create([
                'user_id' => auth()->id(),
                'message' => $request->message
            ]);
        }

        if($request->action === 'close') {
            $ticket->update(['status' => 'closed']);
        } else {
            $ticket->update(['status' => 'answered']);
        }

        return back()->with('status', 'Ticket updated successfully.');
    }
}
