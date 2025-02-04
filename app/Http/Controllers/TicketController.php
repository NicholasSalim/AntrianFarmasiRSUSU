<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller {
    // Generate a new ticket and redirect to ticket details page
    public function generate() {
        $ticket = Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
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
}
