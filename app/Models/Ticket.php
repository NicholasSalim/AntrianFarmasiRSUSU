<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Ticket extends Model {
    use HasFactory;

    protected $fillable = ['ticket_number', 'status'];

    // Function to generate a unique ticket number
    public static function generateTicketNumber() {
        $lastTicket = self::latest()->first();
        $lastNumber = $lastTicket ? intval(substr($lastTicket->ticket_number, 3)) : 0;
        return 'TKT' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    }
}