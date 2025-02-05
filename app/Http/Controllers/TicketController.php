<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller {
    // Generate a new ticket based on queue type
    public function generate(Request $request) {
        $queueType = $request->input('queue_type'); // Get queue type from form

        // Create ticket with next available number for the selected type
        $ticket = Ticket::create([
            'queue_type' => $queueType,
            'ticket_number' => Ticket::generateTicketNumber($queueType),
            'status' => 'pending'
        ]);

        return redirect()->route('ticket.show', $ticket->id);
    }

    // Show the ticket details
    public function show($id) {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.detail', compact('ticket'));
    }

    // List all tickets (optional)
    public function index() {
        $tickets = Ticket::latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function display()
    {
        // Fetch the first active ticket or the first pending ticket
        $currentTicket = Ticket::where('status', 'active')->first() ?? Ticket::where('status', 'pending')->first();
        return view('tickets.display', compact('currentTicket'));
    }

    // Move to the next ticket
    public function next()
    {
        // Mark the current active ticket as completed
        $currentTicket = Ticket::where('status', 'active')->first();
        if ($currentTicket) {
            $currentTicket->update(['status' => 'completed']);
        }

        // Fetch the next pending ticket and mark it as active
        $nextTicket = Ticket::where('status', 'pending')->first();
        if ($nextTicket) {
            $nextTicket->update(['status' => 'active']);
        }

        return redirect()->route('tickets.display');
    }
}
