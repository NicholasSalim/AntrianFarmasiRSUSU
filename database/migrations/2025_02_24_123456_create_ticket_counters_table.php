<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCountersTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_counters', function (Blueprint $table) {
            $table->string('queue_type')->primary(); // e.g., "Regular", "Priority"
            $table->unsignedInteger('last_number')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_counters');
    }
}