<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposException;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller {
    // Generate a new ticket based on queue type
    public function generate(Request $request)
{
    $queueType = $request->input('queue_type'); // Get queue type from form

    // Create ticket with next available number for the selected type
    $ticket = Ticket::create([
        'queue_type' => $queueType,
        'ticket_number' => Ticket::generateTicketNumber($queueType),
        'status' => 'pending'
    ]);

    // Send the ticket to the printer
    try {
        $this->printTicket($ticket);  // Print the generated ticket
    } catch (EscposException $e) {
        return back()->with('error', 'Failed to print the ticket: ' . $e->getMessage());
    }

    return redirect()->route('ticket.generate'); // Redirect back to the ticket generation page
}


private function printTicket($ticket)
{
    // Replace with the correct printer path
    $printerName = "smb://10.52.0.254/EPSON TM-U220 Receipt"; // Using SMB path
    
    try {
        // Attempt connection using the correct printer name
        $connector = new WindowsPrintConnector($printerName);
        $printer = new Printer($connector);

        // Print hospital name in the center
        $printer->selectPrintMode(Printer::MODE_FONT_B);
        $hospitalName = "Prof. Dr. Chairuddin P. Lubis";
        $printer->setEmphasis(true);
        $printer->text($this->centerText("Rumah Sakit"."\n"));
        $printer->text($this->centerText($hospitalName) . "\n");
        $printer->text($this->centerText("Universitas Sumatera Utara"."\n"));
        $printer->setEmphasis(false);
        $printer->text($this->centerText("----------------------------") . "\n");

        // Print other details (center-aligned)
        $printer->text($this->centerText(now()->timezone('Asia/Jakarta')->translatedFormat('j F Y')) . "\n");
        $printer->text($this->centerText(now()->timezone('Asia/Jakarta')->format('H:i:s')) . "\n\n");

        // Print "Nomor Antrian" with centered text and bold ticket number
        $printer->text($this->centerText("NOMOR ANTRIAN ANDA"."\n"));
        $printer->setEmphasis(true);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
        $printer->text($this->centerText("         " . $ticket->ticket_number) . "\n\n");
        $printer->selectPrintMode(Printer::MODE_FONT_B);
        $printer->setEmphasis(false);
        $printer->setTextSize(1, 1); // Reset text size back to normal

        // Print footer (centered)
        $printer->text($this->centerText("Mohon Tunggu Nomor Anda Dipanggil") . "\n");
        $printer->text($this->centerText("Terima kasih sudah mengantri!") . "\n");

        // Cut and close the printer connection
        $printer->cut();
        $printer->close();

        // Log success
        Log::info('Ticket printed successfully.', ['ticket_number' => $ticket->ticket_number]);

        return "Printing successful.";
    } catch (EscposException $e) {
        // Log error if the printer can't be accessed
        Log::error('Error printing ticket.', [
            'ticket_number' => $ticket->ticket_number,
            'error_message' => $e->getMessage(),
        ]);

        return "Printing failed: " . $e->getMessage();
    }
}


// Helper function to center-align text based on the printer's width
private function centerText($text)
{
    $maxWidth = 40; // This can be adjusted based on your printer's character width
    $textLength = strlen($text);
    $spaces = intdiv($maxWidth - $textLength, 2.3); // Calculate the number of leading spaces for centering

    return str_repeat(" ", $spaces) . $text;
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
