<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['queue_type', 'ticket_number', 'status'];

    // Function to generate a unique ticket number and create the ticket atomically
    public static function generateAndCreateTicket($queueType)
    {
        return DB::transaction(function () use ($queueType) {
            // Lock and get or initialize the counter
            $counter = DB::table('ticket_counters')
                ->lockForUpdate()
                ->where('queue_type', $queueType)
                ->first();

            if (!$counter) {
                // First ticket for this queue_type
                DB::table('ticket_counters')->insert([
                    'queue_type' => $queueType,
                    'last_number' => 1
                ]);
                $nextNumber = 1;
            } else {
                // Increment the last number
                $nextNumber = $counter->last_number + 1;
                DB::table('ticket_counters')
                    ->where('queue_type', $queueType)
                    ->update(['last_number' => $nextNumber]);
            }

            // Generate ticket number with prefix (e.g., R-001)
            $prefix = strtoupper(substr($queueType, 0, 1)) . '-';
            $ticketNumber = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create and return the ticket within the transaction
            return self::create([
                'queue_type' => $queueType,
                'ticket_number' => $ticketNumber,
                'status' => 'pending'
            ]);
        });
    }
}