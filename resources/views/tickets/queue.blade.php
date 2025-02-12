@extends('layouts.app')

@section('content')
<div class="flex min-h-screen justify-between px-32" style="padding-top: 150px; padding-bottom: 100px; padding-right: 50px; padding-left: 65px">
    <!-- Left Column: Current Queue Display and Button -->
    <div class="flex flex-col items-center justify-center w-1/2 space-y-4">
        <!-- Header -->
        <h1 class="text-5xl font-bold text-white mb-8" style="font-family: 'Urbanist', sans-serif;">
            Antrian Farmasi
        </h1>

        <!-- Current Queue Display -->
        <div class="bg-white p-16 rounded-3xl shadow-lg mb-8">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-4" style="font-family: 'Urbanist', sans-serif;">
                Nomor Antrian Saat Ini
            </h2>
            <p class="text-6xl font-bold text-center text-gray-800" style="font-family: 'Urbanist', sans-serif;" id="current-ticket">
                {{ $currentTicket->ticket_number ?? 'Tidak ada antrian' }}
            </p>
        </div>

        <!-- Next Ticket Button -->
        <form id="next-ticket-form" action="{{ route('tickets.next') }}" method="POST" onsubmit="return confirmNextTicket()" class="w-full max-w-md">
            @csrf
            <button type="submit" class="w-full px-20 py-4 bg-green-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-green-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
                Panggil Antrian Berikutnya
            </button>
        </form>
    </div>

    <!-- Right Column: List of Pending or Active Tickets -->
    <div class="w-1/2 flex flex-col h-full">

        <div class="flex justify-center mb-6" id="daftar-isi">
            <h2 class="text-3xl font-semibold text-white" style="font-family: 'Urbanist', sans-serif;">
                Daftar Antrian
            </h2>
        </div>

        @php
        $ticketsPerPage = 9;
        $currentPage = request()->query('page', 1);
        $totalPages = ceil($pendingTickets->count() / $ticketsPerPage);
        $currentTickets = $pendingTickets->forPage($currentPage, $ticketsPerPage);
        @endphp

        <!-- Ticket Buttons -->
        <div class="flex justify-center w-full mx-auto space-x-4 mt-4" id="ticket-list">
    @for ($col = 0; $col < 3; $col++)
        <div class="flex flex-col space-y-4">
            @for ($row = 0; $row < 3; $row++)
                @php
                    $index = $row * 3 + $col;
                    $ticket = $currentTickets->skip($index)->first();
                @endphp
                @if($ticket)
                    <form id="ticket-form-{{ $ticket->id }}" action="{{ route('tickets.setCurrent', $ticket->id) }}" method="POST" onsubmit="return confirmSelectTicket('{{ $ticket->ticket_number }}', '{{ $ticket->id }}')">
                        @csrf
                        <button type="submit" class="fixed-size-button bg-white text-3xl text-gray-800 font-bold rounded-3xl shadow-md hover:bg-gray-200 transition duration-200 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
                            {{ $ticket->ticket_number }}
                        </button>
                    </form>
                @else
                    <div class="fixed-size-button opacity-0 pointer-events-none"></div>
                @endif
            @endfor
        </div>
    @endfor
</div>


        
<!-- Pagination -->
<div class="flex justify-center mt-6 space-x-2 items-center">
    @php
        $prevPage = ($currentPage > 1) ? $currentPage - 1 : $totalPages;
        $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : 1;

        $maxPagesToShow = 5;
        $halfRange = floor($maxPagesToShow / 2);

        $startPage = max(1, $currentPage - $halfRange);
        $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

        if ($endPage - $startPage < $maxPagesToShow - 1) {
            $startPage = max(1, $endPage - $maxPagesToShow + 1);
        }
    @endphp

    <!-- Left Arrow -->
    <a href="?page={{ $prevPage }}" class="px-4 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
    <img src="{{ asset('/img/icon/back.png') }}" alt="Previous" class="">
    </a>

    <!-- Show "..." if there's more pages before -->
    @if ($startPage > 1)
        <a href="?page={{ $startPage }}" class="px-4 py-2 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
            ...
        </a>
    @endif

    <!-- Page Number Links -->
    @for ($i = $startPage; $i <= $endPage; $i++)
        <a href="?page={{ $i }}" class="px-4 py-2 font-bold rounded-lg shadow-md transition duration-200
            {{ $i == $currentPage ? 'bg-gray-800 text-white' : 'bg-white text-gray-800 hover:bg-gray-400 cursor-pointer' }}">
            {{ $i }}
        </a>
    @endfor

    <!-- Show "..." if there's more pages after -->
    @if ($endPage < $totalPages)
        <a href="?page={{ $endPage }}" class="px-4 py-2 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
            ...
        </a>
    @endif

    <!-- Right Arrow -->
    <a href="?page={{ $nextPage }}" class="px-4 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
    <img src="{{ asset('/img/icon/next.png') }}" alt="Previous" class="">
    </a>
</div>





    </div> <!-- Closing Right Column -->
</div> <!-- Closing Main Container -->

<!-- Background Blur -->
<div id="next-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>

<!-- Confirmation Box for Next Ticket -->
<div id="next-confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <div id="next-loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>
    <div id="next-confirm-content" class="hidden text-center" style="font-family: 'Urbanist', sans-serif;">
        <h2 class="text-lg font-bold mb-2">Panggil Antrian?</h2>
        <p class="mb-4">Apakah anda ingin memanggil antrian berikutnya?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="closeNextModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">Batal</button>
            <button onclick="proceedToNext()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer">Panggil</button>
        </div>
    </div>
</div>

<!-- Background Blur -->
<div id="ticket-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>

<!-- Confirmation Box for Selecting a Ticket -->
<div id="ticket-confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <div id="ticket-loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>
    <div id="ticket-confirm-content" class="hidden text-center" style="font-family: 'Urbanist', sans-serif;">
        <h2 class="text-lg font-bold mb-2" >Pilih Antrian?</h2>
        <p id="ticket-message" class="mb-4"></p>
        <div class="flex justify-center space-x-4">
            <button onclick="closeTicketModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">Batal</button>
            <button id="confirm-ticket-btn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer">Pilih</button>
        </div>
    </div>
</div>




@endsection
