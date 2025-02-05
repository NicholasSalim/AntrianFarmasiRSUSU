@extends('layouts.app')

@section('content')
<div class="flex min-h-screen px-16 justify-between" style="padding-top: 150px; padding-bottom:100px; padding-left: 100px; padding-right:100px"> <!-- Ensuring both columns align properly -->
    <!-- Left Column: Current Queue Display -->
    <div class="flex flex-col items-start justify-center w-1/2"> <!-- Aligned left without extra padding -->
        <!-- Header -->
        <h1 class="text-5xl font-bold text-white mb-12" style="font-family: 'Urbanist', sans-serif;">
            Sedang Dilayani
        </h1>

        <!-- Current Queue Display -->
        <div class="bg-white p-24 rounded-3xl shadow-lg mb-8 w-3/4 text-center"> <!-- Reduced padding -->
            <p class="text-5xl font-bold text-gray-800" style="font-family: 'Urbanist', sans-serif;"> <!-- Reduced font size -->
                {{ $currentTicket->ticket_number ?? 'Tidak Ada Antrian' }}
            </p>
        </div>
    </div>

   <!-- Right Column: List of Pending Tickets (2x2 Card Layout) -->
    <div class="w-1/2 flex flex-col justify-center items-end"> <!-- Ensured alignment -->
        <h2 class="text-5xl font-bold text-white mb-2" style="font-family: 'Urbanist', sans-serif;">
            Daftar Antrian
        </h2>
        
        <div class="w-full flex flex-col gap-4 space-4"> <!-- Container for two rows -->
            @foreach ($pendingTickets->take(4)->chunk(2) as $row) <!-- Split into rows of 2 -->
                <div class="flex justify-center gap-4"> <!-- Row with two columns -->
                    @foreach ($row as $ticket)
                        <div class="bg-white p-16 rounded-3xl shadow-md flex items-center justify-center text-xl font-semibold text-gray-700 h-32 w-1/2"> <!-- Card size adjusted -->
                            <p class="text-5xl font-bold text-gray-800 " style="font-family: 'Urbanist', sans-serif;">
                                {{ $ticket->ticket_number }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $pendingTickets->links() }}
        </div>
    </div>

</div>  
@endsection
