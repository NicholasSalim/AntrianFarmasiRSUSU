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

    public function queue()
{
    // Fetch the first active ticket or the first pending ticket
    $currentTicket = Ticket::where('status', 'active')->first(); 

// Ensure the first ticket becomes active if no active ticket exists
if (!$currentTicket) {
    $currentTicket = Ticket::where('status', 'pending')->orderBy('created_at', 'asc')->first();
    if ($currentTicket) {
        $currentTicket->update(['status' => 'active']); // Mark as active
    }
}

// Get pending tickets (excluding the active one)
$pendingTickets = Ticket::where('status', 'pending')
                        ->where('id', '!=', optional($currentTicket)->id)
                        ->orderBy('created_at', 'asc')
                        ->get();
    // Pass both current ticket and pending tickets to the view
    return view('tickets.queue', compact('currentTicket', 'pendingTickets'));
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

        return redirect()->route('tickets.queue');
    }

    public function setCurrent($id)
{
    // Mark the current active ticket as completed
    $currentTicket = Ticket::where('status', 'active')->first();
    if ($currentTicket) {
        $currentTicket->update(['status' => 'completed']);
    }

    // Set the selected ticket as active
    $newCurrentTicket = Ticket::findOrFail($id);
    $newCurrentTicket->update(['status' => 'active']);

    return redirect()->route('tickets.queue');
}
}
