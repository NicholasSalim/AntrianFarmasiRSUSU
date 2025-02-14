<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket; // Assuming you have a Ticket model

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
    protected $description = 'Clear all tickets from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Delete all tickets from the database
        Ticket::truncate(); // This will delete all rows from the tickets table

        $this->info('All tickets have been cleared successfully.');
    }
}