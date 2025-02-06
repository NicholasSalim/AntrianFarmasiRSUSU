

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
        console.log('Speaking:', ticketNumber);  // Log the ticket number being spoken

        var msg = new SpeechSynthesisUtterance('Tiket Nomor ' + ticketNumber + ',Silahkan datang ke konter');
        msg.lang = 'id-ID';
        msg.rate = 0.85;
        msg.pitch = 0.8;
        window.speechSynthesis.speak(msg);
    } else {
        console.log('No valid ticket number to speak.');
    }
}


    // Set an interval to fetch updated content every 5 seconds
    setInterval(function() {
        updateTicketList();
    }, 5000); // Refresh every 5 seconds
