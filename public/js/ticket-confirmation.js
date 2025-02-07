let selectedQueueType = '';

//confirm generate ticket
function confirmTicket(queueType) {
    selectedQueueType = queueType;
    document.getElementById('queue-type').innerText = 'Tipe ' + queueType;

    // Show blur background
    document.getElementById('blur-overlay').classList.remove('hidden');

    // Show confirmation box
    let confirmBox = document.getElementById('confirm-box');
    confirmBox.classList.remove('hidden');
    confirmBox.style.opacity = 0;
    setTimeout(() => { confirmBox.style.opacity = 1; }, 100); // Smooth fade-in

    // Show loading animation first, then content
    document.getElementById('loading-animation').classList.remove('hidden');
    document.getElementById('confirm-content').classList.add('hidden');
    setTimeout(() => {
        document.getElementById('loading-animation').classList.add('hidden');
        document.getElementById('confirm-content').classList.remove('hidden');
    }, 1000); // Simulating a 1-second loading time

    return false; // Prevent form submission
}

function proceedToGenerate() {
    document.getElementById(`ticket-form-${selectedQueueType.toLowerCase()}`).submit();
}

function closeModal() {
    document.getElementById('confirm-box').classList.add('hidden');
    document.getElementById('blur-overlay').classList.add('hidden');
}


//confirm next ticket
function confirmNextTicket() {
    document.getElementById('next-blur-overlay').classList.remove('hidden');

    let confirmBox = document.getElementById('next-confirm-box');
    confirmBox.classList.remove('hidden');
    confirmBox.style.opacity = 0;
    setTimeout(() => { confirmBox.style.opacity = 1; }, 100); // Smooth fade-in

    document.getElementById('next-loading-animation').classList.remove('hidden');
    document.getElementById('next-confirm-content').classList.add('hidden');
    setTimeout(() => {
        document.getElementById('next-loading-animation').classList.add('hidden');
        document.getElementById('next-confirm-content').classList.remove('hidden');
    }, 1000); // Simulated 1-second loading

    return false; // Prevent form submission
}

function proceedToNext() {
    document.getElementById('next-ticket-form').submit();
}

function closeNextModal() {
    document.getElementById('next-confirm-box').classList.add('hidden');
    document.getElementById('next-blur-overlay').classList.add('hidden');
}


//confirm select ticket
function confirmSelectTicket(ticketNumber, ticketId) {
    document.getElementById('ticket-blur-overlay').classList.remove('hidden');

    let confirmBox = document.getElementById('ticket-confirm-box');
    confirmBox.classList.remove('hidden');
    confirmBox.style.opacity = 0;
    setTimeout(() => { confirmBox.style.opacity = 1; }, 100); // Smooth fade-in

    document.getElementById('ticket-loading-animation').classList.remove('hidden');
    document.getElementById('ticket-confirm-content').classList.add('hidden');
    
    // Show loading effect for 1 second before confirmation
    setTimeout(() => {
        document.getElementById('ticket-loading-animation').classList.add('hidden');
        document.getElementById('ticket-confirm-content').classList.remove('hidden');

        // Bold the ticket number in the message
        document.getElementById('ticket-message').innerHTML = `Apakah anda ingin memilih antrian <strong>${ticketNumber}</strong>?`;

        // Set the correct form to submit when confirmed
        document.getElementById('confirm-ticket-btn').setAttribute('onclick', `proceedToSelect('${ticketId}')`);
    }, 1000);

    return false; // Prevent form submission
}


function proceedToSelect(ticketId) {
    document.getElementById(`ticket-form-${ticketId}`).submit();
}

function closeTicketModal() {
    document.getElementById('ticket-confirm-box').classList.add('hidden');
    document.getElementById('ticket-blur-overlay').classList.add('hidden');
}
