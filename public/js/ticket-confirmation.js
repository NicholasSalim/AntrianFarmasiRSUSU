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
    }, 250); // Simulating a 1-second loading time

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
    }, 250); // Simulated 1-second loading

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
    }, 250);

    return false; // Prevent form submission
}


function proceedToSelect(ticketId) {
    document.getElementById(`ticket-form-${ticketId}`).submit();
}

function closeTicketModal() {
    document.getElementById('ticket-confirm-box').classList.add('hidden');
    document.getElementById('ticket-blur-overlay').classList.add('hidden');
}

// Confirm calling a ticket by type
function confirmCallByType(type) {
    // Check if there are pending tickets for this type
    if (window.pendingCounts[type] === 0) {
        showErrorModal(`Tidak ada antrian untuk tipe ${type}`);
        return false; // Prevent further action
    }

    // Get the last called ticket for this type
    let lastCalled = window.lastCalledTickets[type];
    let message = lastCalled 
        ? `Tiket ${type} terakhir dipanggil: <strong>${lastCalled}</strong>` 
        : `Belum ada tiket ${type} yang dipanggil`;
    message += `<br>Apakah Anda yakin ingin memanggil antrian <strong>${type}</strong> berikutnya?`;

    // Show blur overlay
    document.getElementById('call-by-type-blur-overlay').classList.remove('hidden');

    // Show confirmation box with fade-in
    let confirmBox = document.getElementById('call-by-type-confirm-box');
    confirmBox.classList.remove('hidden');
    confirmBox.style.opacity = 0;
    setTimeout(() => { confirmBox.style.opacity = 1; }, 100);

    // Show loading animation first
    document.getElementById('call-by-type-loading-animation').classList.remove('hidden');
    document.getElementById('call-by-type-confirm-content').classList.add('hidden');

    // After a short delay, show the confirmation content
    setTimeout(() => {
        document.getElementById('call-by-type-loading-animation').classList.add('hidden');
        document.getElementById('call-by-type-confirm-content').classList.remove('hidden');
        document.getElementById('call-by-type-message').innerHTML = message;

        // Set the confirm button to submit the correct form
        document.getElementById('confirm-call-by-type-btn').setAttribute('onclick', `proceedToCallByType('${type}')`);
    }, 250);

    return false; // Prevent immediate form submission
}

// Proceed with calling the ticket by type
function proceedToCallByType(type) {
    document.querySelector(`form[data-type="${type}"]`).submit();
}

// Close the call-by-type confirmation modal
function closeCallByTypeModal() {
    document.getElementById('call-by-type-confirm-box').classList.add('hidden');
    document.getElementById('call-by-type-blur-overlay').classList.add('hidden');
}

// Function to show the error modal with a message
function showErrorModal(message) {
    // Show the blur overlay
    document.getElementById('error-blur-overlay').classList.remove('hidden');

    // Show the error box with a fade-in effect
    let errorBox = document.getElementById('error-box');
    errorBox.classList.remove('hidden');
    errorBox.style.opacity = 0;
    setTimeout(() => { errorBox.style.opacity = 1; }, 100); // Smooth fade-in

    // Set the error message
    document.getElementById('error-message').innerText = message;
}

// Function to close the error modal
function closeErrorModal() {
    document.getElementById('error-box').classList.add('hidden');
    document.getElementById('error-blur-overlay').classList.add('hidden');
}