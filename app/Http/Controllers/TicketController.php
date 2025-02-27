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

        // Generate and create ticket in one atomic operation
        $ticket = Ticket::generateAndCreateTicket($queueType);

        // Send the ticket to the printer
        try {
            $this->printTicket($ticket);  // Print the generated ticket
        } catch (EscposException $e) {
            return back()->with('error', 'Failed to print the ticket: ' . $e->getMessage());
        }

        return redirect()->route('ticket.generate'); // Redirect back to the ticket generation page
    }

public function nextByType($queueType)
    {
        // Mark the current active ticket as completed
        $currentTicket = Ticket::where('status', 'active')->first();
        if ($currentTicket) {
            $currentTicket->update(['status' => 'completed']);
        }

        // Fetch the next pending ticket of the specified queue type
        $nextTicket = Ticket::where('status', 'pending')
                            ->where('queue_type', $queueType)
                            ->orderBy('created_at', 'asc')
                            ->first();

        if ($nextTicket) {
            $nextTicket->update(['status' => 'active']);
        }

        return redirect()->route('tickets.queue');
    }


    private function printTicket($ticket)
    {
        // Define the printer UNC path (using double backslashes for Windows UNC)
        $printerUNC = "\\\\10.52.3.180\\EPSON TM-U220 Receipt";
        // Create a temporary file to store the raw ESC/POS data
        $tempFile = tempnam(sys_get_temp_dir(), 'ticket');
    
        try {
            // Use FilePrintConnector to write the ESC/POS data to the temporary file
            $connector = new \Mike42\Escpos\PrintConnectors\FilePrintConnector($tempFile);
            $printer = new \Mike42\Escpos\Printer($connector);
    
            // ------------------ Begin Printing Commands ------------------
    
            // Print header information (center-aligned)
            $printer->selectPrintMode(\Mike42\Escpos\Printer::MODE_FONT_B);
            $hospitalName = "Prof. Dr. Chairuddin P. Lubis";
            $printer->setEmphasis(true);
            $printer->text($this->centerText("Rumah Sakit" . "\n"));
            $printer->text($this->centerText($hospitalName) . "\n");
            $printer->text($this->centerText("Universitas Sumatera Utara" . "\n"));
            $printer->setEmphasis(false);
            $printer->text($this->centerText("----------------------------") . "\n");
    
            // Print date and time details (centered)
            $printer->text($this->centerText(now()->timezone('Asia/Jakarta')->translatedFormat('j F Y')) . "\n");
            $printer->text($this->centerText(now()->timezone('Asia/Jakarta')->format('H:i:s')) . "\n\n");
    
            // Print ticket number with emphasis and larger font size
            $printer->text($this->centerText("NOMOR ANTRIAN ANDA" . "\n"));
            $printer->setEmphasis(true);
            $printer->selectPrintMode(\Mike42\Escpos\Printer::MODE_DOUBLE_WIDTH | \Mike42\Escpos\Printer::MODE_DOUBLE_HEIGHT);
            $printer->text($this->centerText("         " . $ticket->ticket_number) . "\n\n");
            $printer->selectPrintMode(\Mike42\Escpos\Printer::MODE_FONT_B);
            $printer->setEmphasis(false);
            $printer->setTextSize(1, 1); // Reset text size
    
            // Print footer message (centered)
            $printer->text($this->centerText("Mohon Tunggu Nomor Anda Dipanggil") . "\n");
            $printer->text($this->centerText("Terima kasih sudah mengantri!") . "\n");
    
            // Cut paper and close the connection to flush data to the file
            $printer->cut();
            $printer->close();
    
            // ------------------ End Printing Commands ------------------
    
            // Now use the copy command in binary mode (/B) to send the file to the printer
            $command = "copy /B " . escapeshellarg($tempFile) . " " . escapeshellarg($printerUNC);
            exec($command, $output, $return_var);
    
            // Check for errors from the copy command
            if ($return_var !== 0) {
                throw new \Exception("Copy command failed with status $return_var. Output: " . implode("\n", $output));
            }
    
            // Delete the temporary file once printing is complete
            unlink($tempFile);
    
            // Log the successful print job
            \Illuminate\Support\Facades\Log::info('Ticket printed successfully.', ['ticket_number' => $ticket->ticket_number]);
            return "Printing successful.";
        } catch (\Exception $e) {
            // Ensure the temporary file is removed in case of errors
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            \Illuminate\Support\Facades\Log::error('Error printing ticket.', [
                'ticket_number' => $ticket->ticket_number,
                'error_message' => $e->getMessage(),
            ]);
            throw new \Mike42\Escpos\EscposException($e->getMessage());
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

    public function selfprint()
{
    $lastTickets = $this->getLastTickets(); // Fetch last issued ticket numbers
    return view('tickets.selfprint', compact('lastTickets')); // Pass it to the view
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

    // Pagination setup
    $ticketsPerPage = 9;
    $currentPage = request()->query('page', 1); // Get current page from query parameter, default to 1
    $pendingTickets = Ticket::where('status', 'pending')
                            ->where('id', '!=', optional($currentTicket)->id)
                            ->orderBy('created_at', 'asc')
                            ->get(); // Fetch all pending tickets
    $totalTickets = $pendingTickets->count();
    $totalPages = ceil($totalTickets / $ticketsPerPage);
    $currentTickets = $pendingTickets->forPage($currentPage, $ticketsPerPage); // Paginate the collection

    // Fetch last called tickets and pending counts for each type
    $lastCalledTickets = [];
    $pendingCounts = [];
    foreach (['A', 'B', 'R'] as $type) {
        $lastCalled = Ticket::where('queue_type', $type)
                            ->whereIn('status', ['active', 'completed'])
                            ->orderBy('updated_at', 'desc')
                            ->first();
        $lastCalledTickets[$type] = $lastCalled ? $lastCalled->ticket_number : null;

        $pendingCounts[$type] = Ticket::where('queue_type', $type)
                                      ->where('status', 'pending')
                                      ->count();
    }

    return view('tickets.queue', compact(
        'currentTicket',
        'pendingTickets',
        'currentTickets',
        'currentPage',
        'totalPages',
        'lastCalledTickets',
        'pendingCounts'
    ));
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
    // Truncate the tickets table
    Ticket::truncate();
    
    // Reset ticket_counters by truncating it (or setting last_number to 0)
    DB::table('ticket_counters')->truncate(); // Option 1: Wipe all counters
    // Alternatively, reset all last_number to 0 (if you want to keep queue_type entries):
    // DB::table('ticket_counters')->update(['last_number' => 0]);

    return redirect()->route('ticket.generate')->with('success', 'All tickets have been cleared and ticket numbers reset.');
}
}
