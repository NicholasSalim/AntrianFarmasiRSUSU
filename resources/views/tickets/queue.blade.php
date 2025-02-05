@extends('layouts.app')

@section('content')
<div class="flex min-h-screen justify-between px-32" style="padding-top: 150px; padding-bottom: 100px; padding-right: 50px; padding-left: 65px">
    <!-- Left Column: Current Queue Display and Button -->
    <div class="flex flex-col items-center justify-center w-1/2 space-y-4">
        <!-- Header -->
        <h1 class="text-5xl font-bold text-gray-800 mb-8" style="font-family: 'Urbanist', sans-serif;">
            Antrian Farmasi
        </h1>

        <!-- Current Queue Display -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
            <h2 class="text-3xl font-semibold text-gray-700 mb-4" style="font-family: 'Urbanist', sans-serif;">
                Nomor Antrian Saat Ini
            </h2>
            <p class="text-6xl font-bold text-green-600" style="font-family: 'Urbanist', sans-serif;">
                {{ $currentTicket->ticket_number ?? 'Tidak ada antrian' }}
            </p>
        </div>

        <!-- Button: Next Ticket -->
        <form action="{{ route('tickets.next') }}" method="POST" class="w-full max-w-md">
            @csrf
            <button type="submit" class="w-full px-32 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md hover:bg-green-500 transition duration-300" style="font-family: 'Urbanist', sans-serif;">
                Panggil Antrian Berikutnya
            </button>
        </form>
    </div>

    <!-- Right Column: List of Pending or Active Tickets -->
    <div class="w-1/2 flex flex-col pl-12">
        <h2 class="text-3xl font-semibold text-gray-700 mb-6" style="font-family: 'Urbanist', sans-serif;">
            Daftar Antrian
        </h2>

        @php
        $ticketsPerPage = 9; // Number of tickets per page
        $currentPage = request()->query('page', 1); // Get current page from query string
        $totalPages = ceil($pendingTickets->count() / $ticketsPerPage); // Calculate total pages

        // Get tickets for the current page using forPage()
        $currentTickets = $pendingTickets->forPage($currentPage, $ticketsPerPage);
        @endphp

        <!-- Ticket Buttons -->
        <div class="flex justify-center w-full mx-auto space-x-4 mt-4">
            @for ($col = 0; $col < 3; $col++)
                <div class="flex flex-col space-y-4">
                    @for ($row = 0; $row < 3; $row++)
                        @php
                            $index = $row * 3 + $col; // Reorder items column-wise
                            $ticket = $currentTickets->skip($index)->first(); // Get the correct ticket
                        @endphp
                        @if($ticket)
                            <form action="{{ route('tickets.setCurrent', $ticket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-white text-3xl text-gray-800 font-bold py-8 px-20 rounded-lg shadow-md hover:bg-gray-200 transition duration-200 cursor-pointer">
                                    {{ $ticket->ticket_number }}
                                </button>
                            </form>
                        @else
                            <!-- Placeholder to maintain layout -->
                            <div class="py-8 px-20"></div>
                        @endif
                    @endfor
                </div>
            @endfor
        </div>

        <!-- Pagination Buttons -->
        <div class="flex justify-center mt-6 space-x-6">
            @if ($currentPage > 1)
                <a href="?page={{ $currentPage - 1 }}" class="px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200">Previous</a>
            @endif

            @if ($currentPage < $totalPages)
                <a href="?page={{ $currentPage + 1 }}" class="px-6 py-3 bg-gray-300 text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200">Next</a>
            @endif
        </div>
    </div>
</div>
@endsection