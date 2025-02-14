<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

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

    // Fetch last issued ticket for each queue type
    private function getLastTickets()
    {
        return Ticket::select('queue_type', 'ticket_number', 'created_at')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('tickets')
                    ->groupBy('queue_type');
            })
            ->get()
            ->keyBy('queue_type');
    }

    public function ticketSelection()
{
    $lastTickets = $this->getLastTickets(); // Fetch last issued ticket numbers
    return view('tickets.generate', compact('lastTickets')); // Pass it to the view
}




    // Ticket selection page with last issued tickets
    public function index() {
        $tickets = Ticket::latest()->get();
        $lastTickets = $this->getLastTickets();

        return view('tickets.index', compact('tickets', 'lastTickets'));
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

    public function showQueue()
    {
        $currentTicket = Ticket::where('status', 'active')->latest()->first();
        $pendingTickets = Ticket::where('status', 'pending')->orderBy('created_at')->paginate(4);
        $remainingTicketsCount = Ticket::where('status', 'pending')->count(); // Count pending tickets

        return view('tickets.display', compact('currentTicket', 'pendingTickets', 'remainingTicketsCount'));
    }

    public function clear(Request $request)
    {
        Ticket::truncate(); // Deletes all records from the tickets table

        return redirect()->route('ticket.generate')->with('success', 'All tickets have been cleared.');
    }
}
