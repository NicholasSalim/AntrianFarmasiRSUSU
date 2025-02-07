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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Function to update ticket content dynamically and play TTS when ticket changes
    function updateTicketList() {
    console.log("updateTicketList() is running...");  // Log to confirm the function is being triggered

    $.ajax({
        url: window.location.href, // Current page URL to fetch the content
        type: 'GET',
        success: function(response) {
            // Get the new ticket number from the response
            var newCurrentTicket = $(response).find('#current-ticket');
            var newTicketNumber = newCurrentTicket.text().trim();

            // Log the current and new ticket numbers to debug
            var currentTicketText = $('#current-ticket').text().trim();

            // Only trigger TTS if the ticket number has changed
            if (newTicketNumber !== currentTicketText && newTicketNumber !== 'Tidak Ada Antrian') {
                speakTicketNumber(newTicketNumber);  // Call the TTS function
            } else {
                console.log("Ticket number has not changed, no TTS triggered.");
            }

            // Update the DOM with the new ticket number
            $('#current-ticket').replaceWith(newCurrentTicket);

            // Update the pending ticket list
            var newTicketList = $(response).find('#ticket-list');
            $('#ticket-list').replaceWith(newTicketList);
        },
        error: function() {
            console.log('Error fetching updated content.');
        }
    });
}


    // Function to play the TTS sound
    function speakTicketNumber(ticketNumber) {
    if (ticketNumber && ticketNumber !== 'Tidak Ada Antrian') {
        console.log('Speaking:', ticketNumber);

        // Cancel any ongoing speech
        window.speechSynthesis.cancel();

        // Add a small delay to ensure the API is ready
        setTimeout(function() {
            var msg = new SpeechSynthesisUtterance('Tiket Nomor ' + ticketNumber + ', Silahkan datang ke konter');
            msg.lang = 'id-ID';
            msg.rate = 0.85;
            msg.pitch = 0.8;
            window.speechSynthesis.speak(msg);
        }, 500); // 500ms delay
    } else {
        console.log('No valid ticket number to speak.');
    }
}


    // Set an interval to fetch updated content every 5 seconds
    setInterval(function() {
        updateTicketList();
    }, 5000); // Refresh every 5 seconds
</script>
@endsection
