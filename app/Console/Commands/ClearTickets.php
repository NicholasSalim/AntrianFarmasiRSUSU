<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class ClearTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all tickets and reset ticket counters';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Delete all tickets from the database
        Ticket::truncate(); // Clears the tickets table
        
        // Reset ticket counters to start from 001
        DB::table('ticket_counters')->truncate(); // Wipes ticket_counters
        // Alternative: DB::table('ticket_counters')->update(['last_number' => 0]); // Resets to 0, keeps queue_type rows

        $this->info('All tickets have been cleared and ticket numbers reset successfully.');
    }
}