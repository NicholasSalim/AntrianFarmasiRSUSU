@extends('layouts.app2')

@section('content')
<div class="flex min-h-screen px-16 justify-between" style="padding-top: 100px; padding-bottom:50px; padding-left: 100px; padding-right:100px">
    <!-- Left Column: Current Queue Display -->
    <div class="flex flex-col items-start justify-center w-1/2">
        <!-- Header -->
        <h1 class="text-5xl font-bold text-white mb-12" style="font-family: 'Urbanist', sans-serif;">
            Sedang Dilayani
        </h1>

        <!-- Current Queue Display -->
        <div class="bg-white p-24 rounded-3xl shadow-lg mb-8 w-3/4 text-center">
            <p class="text-6xl font-bold text-gray-800" style="font-family: 'Urbanist', sans-serif;" id="current-ticket">
                {{ $currentTicket->ticket_number ?? 'Tidak Ada Antrian' }}
            </p>
        </div>
    </div>

    <!-- Right Column: List of Pending Tickets -->
    <div class="w-1/2 flex flex-col pl-12" id="pending-tickets">
        <h2 class="text-3xl font-semibold text-white mb-6" style="font-family: 'Urbanist', sans-serif;">
            Daftar Antrian
        </h2>

        @php
            $ticketsPerPage = 4; // 2x2 Layout (4 tickets per page)
            $currentPage = request()->query('page', 1);
            $totalPages = ceil($pendingTickets->count() / $ticketsPerPage);

            // Get tickets for the current page using forPage()
            $currentTickets = $pendingTickets->forPage($currentPage, $ticketsPerPage);
        @endphp

        <!-- Ticket Display (2 Columns x 2 Rows) -->
        <div class="flex justify-center w-full mx-auto space-x-8 mt-4" id="ticket-list">
            @for ($col = 0; $col < 2; $col++)
                <div class="flex flex-col space-y-8">
                    @for ($row = 0; $row < 2; $row++)
                        @php
                            $index = $row * 2 + $col; // Reorder items column-wise
                            $ticket = $currentTickets->skip($index)->first(); // Get the correct ticket
                        @endphp
                        @if($ticket)
                            <div class="bg-white text-6xl text-gray-800 p-24 font-bold py-10 px-16 rounded-3xl shadow-md flex items-center justify-center w-48 h-40"  style="font-family: 'Urbanist', sans-serif;">
                                {{ $ticket->ticket_number }}
                            </div>
                        @else
                            <!-- Placeholder to maintain layout -->
                            <div class="py-10 px-16 w-48 h-40"></div>
                        @endif
                    @endfor
                </div>
            @endfor
        </div>

        <!-- Pagination Buttons -->
        <div class="flex justify-center mt-6 space-x-6">
            @if ($currentPage > 1)
                <a href="?page={{ $currentPage - 1 }}" class="px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200">
                    Previous
                </a>
            @endif

            @if ($currentPage < $totalPages)
                <a href="?page={{ $currentPage + 1 }}" class="px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200">
                    Next
                </a>
            @endif
        </div>
    </div>
</div>



@endsection
