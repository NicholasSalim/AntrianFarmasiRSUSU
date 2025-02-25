// Function to update ticket content dynamically
    function updateTicketList() {
        $.ajax({
            url: window.location.href, // Current page URL to fetch the content
            type: 'GET',
            success: function(response) {
                // Update current ticket
                var newCurrentTicket = $(response).find('#current-ticket').html();
                $('#current-ticket').html(newCurrentTicket);

                // Update the pending ticket list
                var newTicketList = $(response).find('#ticket-list').html();
                $('#ticket-list').html(newTicketList);
            },
            error: function() {
                console.log('Error fetching updated content.');
            }
        });
    }

    // Set an interval to fetch updated content every 5 seconds
    setInterval(function() {
        updateTicketList();
    }, 1000); // Refresh every 5 seconds
