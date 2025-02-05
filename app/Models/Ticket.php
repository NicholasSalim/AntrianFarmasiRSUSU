<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    use HasFactory;

    protected $fillable = ['queue_type', 'ticket_number', 'status'];

    // Function to generate a unique ticket number based on queue type
    public static function generateTicketNumber($queueType) {
        $prefix = strtoupper($queueType) . '-'; // Prefix (A-, B-, R-)

        // Get last ticket for this type
        $lastTicket = self::where('queue_type', $queueType)->latest()->first();
        $lastNumber = $lastTicket ? intval(substr($lastTicket->ticket_number, 2)) : 0;

        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
