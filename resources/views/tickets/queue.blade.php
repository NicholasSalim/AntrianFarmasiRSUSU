@extends('layouts.app')

@section('content')
<div class="flex min-h-screen justify-between px-32" style="padding-top: 150px; padding-bottom: 100px; padding-right: 50px; padding-left: 65px">
    <!-- Left Column: Current Queue Display and Buttons -->
    <div class="flex flex-col items-center justify-center w-1/2 space-y-4">
        <h1 class="text-5xl font-bold text-white mb-8" style="font-family: 'Urbanist', sans-serif;">
            Antrian Farmasi
        </h1>
        <div class="bg-white p-16 rounded-3xl shadow-lg mb-8">
            <h2 class="text-3xl font-semibold text-center text-gray-700 mb-4" style="font-family: 'Urbanist', sans-serif;">
                Nomor Antrian Saat Ini
            </h2>
            <p class="text-6xl font-bold text-center text-gray-800" style="font-family: 'Urbanist', sans-serif;" id="current-ticket">
                {{ $currentTicket->ticket_number ?? 'Tidak ada antrian' }}
            </p>
        </div>
        <div class="w-full max-w-md space-y-6" id="button-section">
            <form id="next-ticket-form" action="{{ route('tickets.next') }}" method="POST" onsubmit="return confirmNextTicket()">
                @csrf
                <button type="submit" class="w-full px-20 py-4 bg-green-600 text-white text-xl font-semibold rounded-lg shadow-md hover:bg-green-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
                    Panggil Antrian Berikutnya
                </button>
            </form>
            <div class="flex justify-between space-x-2 mt-6">
                <form action="{{ route('tickets.nextByType', 'A') }}" method="POST" onsubmit="return confirmCallByType('A')" data-type="A">
                    @csrf
                    <button type="submit" class="w-full px-6 py-5 bg-blue-600 text-white text-xl font-semibold rounded-lg shadow-md hover:bg-blue-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
                        Panggil A
                    </button>
                </form>
                <form action="{{ route('tickets.nextByType', 'B') }}" method="POST" onsubmit="return confirmCallByType('B')" data-type="B">
                    @csrf
                    <button type="submit" class="w-full px-6 py-5 bg-gray-800 text-white text-xl font-semibold rounded-lg shadow-md hover:bg-gray-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
                        Panggil B
                    </button>
                </form>
                <form action="{{ route('tickets.nextByType', 'R') }}" method="POST" onsubmit="return confirmCallByType('R')" data-type="R">
                    @csrf
                    <button type="submit" class="w-full px-6 py-5 bg-red-600 text-white text-xl font-semibold rounded-lg shadow-md hover:bg-red-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
                        Panggil R
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: List of Pending or Active Tickets -->
    <div class="w-1/2 flex flex-col h-full">
        <div class="flex justify-center mb-6" id="daftar-isi">
            <h2 class="text-3xl font-semibold text-white" style="font-family: 'Urbanist', sans-serif;">
                Daftar Antrian
            </h2>
        </div>
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
        <div class="flex justify-center mt-6 space-x-2 items-center" id="pagination">
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
            <a href="?page={{ $prevPage }}" class="px-4 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
                <img src="{{ asset('/img/icon/back.png') }}" alt="Previous" class="">
            </a>
            @if ($startPage > 1)
                <a href="?page={{ $startPage }}" class="px-4 py-2 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
                    ...
                </a>
            @endif
            @for ($i = $startPage; $i <= $endPage; $i++)
                <a href="?page={{ $i }}" class="px-4 py-2 font-bold rounded-lg shadow-md transition duration-200 {{ $i == $currentPage ? 'bg-gray-800 text-white' : 'bg-white text-gray-800 hover:bg-gray-400 cursor-pointer' }}">
                    {{ $i }}
                </a>
            @endfor
            @if ($endPage < $totalPages)
                <a href="?page={{ $endPage }}" class="px-4 py-2 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
                    ...
                </a>
            @endif
            <a href="?page={{ $nextPage }}" class="px-4 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-400 transition duration-200 cursor-pointer">
                <img src="{{ asset('/img/icon/next.png') }}" alt="Next" class="">
            </a>
        </div>
    </div>
</div>

<!-- Confirmation Boxes -->
<div id="next-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>
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

<div id="ticket-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>
<div id="ticket-confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <div id="ticket-loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>
    <div id="ticket-confirm-content" class="hidden text-center" style="font-family: 'Urbanist', sans-serif;">
        <h2 class="text-lg font-bold mb-2">Pilih Antrian?</h2>
        <p id="ticket-message" class="mb-4"></p>
        <div class="flex justify-center space-x-4">
            <button onclick="closeTicketModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">Batal</button>
            <button id="confirm-ticket-btn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer">Pilih</button>
        </div>
    </div>
</div>

<div id="call-by-type-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>
<div id="call-by-type-confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <div id="call-by-type-loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>
    <div id="call-by-type-confirm-content" class="hidden text-center" style="font-family: 'Urbanist', sans-serif;">
        <h2 class="text-lg font-bold mb-2">Panggil Antrian?</h2>
        <p id="call-by-type-message" class="mb-4"></p>
        <div class="flex justify-center space-x-4">
            <button onclick="closeCallByTypeModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">Batal</button>
            <button id="confirm-call-by-type-btn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer">Panggil</button>
        </div>
    </div>
</div>

<div id="error-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>
<div id="error-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <div id="error-content" class="text-center" style="font-family: 'Urbanist', sans-serif;">
        <h2 class="text-lg font-bold mb-2">Tidak Ada Antrian</h2>
        <p id="error-message" class="mb-4"></p>
        <div class="flex justify-center">
            <button onclick="closeErrorModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">OK</button>
        </div>
    </div>
</div>

<!-- Pass Initial Data to JavaScript -->
<script>
    window.lastCalledTickets = @json($lastCalledTickets);
    window.pendingCounts = @json($pendingCounts);
</script>

<!-- jQuery and AJAX Refresh Logic -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updatePageContent() {
        $.ajax({
            url: window.location.href,
            type: 'GET',
            success: function(response) {
                var newCurrentTicket = $(response).find('#current-ticket').html();
                if ($('#current-ticket').html() !== newCurrentTicket) {
                    $('#current-ticket').html(newCurrentTicket);
                }

                var newTicketList = $(response).find('#ticket-list').html();
                if ($('#ticket-list').html() !== newTicketList) {
                    $('#ticket-list').html(newTicketList);
                }

                var newPagination = $(response).find('#pagination').html();
                if ($('#pagination').html() !== newPagination) {
                    $('#pagination').html(newPagination);
                }

                var newButtonSection = $(response).find('#button-section').html();
                if ($('#button-section').html() !== newButtonSection) {
                    $('#button-section').html(newButtonSection);
                }

                var newPendingCountsScript = $(response).find('script:contains("window.pendingCounts")').html();
                if (newPendingCountsScript) eval(newPendingCountsScript);

                var newLastCalledTicketsScript = $(response).find('script:contains("window.lastCalledTickets")').html();
                if (newLastCalledTicketsScript) eval(newLastCalledTicketsScript);
            },
            error: function(xhr, status, error) {
                console.log('Error fetching updated content:', error);
            }
        });
    }

    setInterval(function() {
        updatePageContent();
    }, 1000);

    $(document).ready(function() {
        updatePageContent();
    });
</script>

<script src="{{ asset('js/ticket-confirmation.js') }}"></script>
@endsection