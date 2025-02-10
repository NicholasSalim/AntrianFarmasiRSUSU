@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center">
    <!-- Header -->
    <h1 class="text-7xl font-bold text-white mb-6" style="font-family: 'Urbanist', sans-serif;">
        Ambil Antrian
    </h1>
    <!-- Caption -->
    <p class="text-2xl text-white mb-12" style="font-family: 'Urbanist', sans-serif;">
        Pilih jenis antrian yang ingin diambil
    </p>

    <!-- Ticket Generation Cards -->
    <div class="container mx-auto flex justify-center space-x-6 my-6">
        <form id="ticket-form-a" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('A')">
            @csrf
            <input type="hidden" name="queue_type" value="A">
            <button type="submit" class="block w-64 h-64 bg-white mx-6 p-6 rounded-lg shadow-md shadow-black hover:shadow-lg hover:bg-gray-400 transition duration-300 cursor-pointer flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold mb-6 p-2" style="font-family: 'Urbanist', sans-serif;">Antrian</h2>
                <img src="/img/icon/a.png" alt="Tiket A" class="w-20 h-20 px-4 py-4" style="width: 100px; height: 100px;">
            </button>
        </form>

        <form id="ticket-form-b" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('B')">
            @csrf
            <input type="hidden" name="queue_type" value="B">
            <button type="submit" class="block w-64 h-64 bg-white mx-6 p-6 rounded-lg shadow-md shadow-black hover:shadow-lg hover:bg-gray-400 transition duration-300 cursor-pointer flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold mb-6 p-2" style="font-family: 'Urbanist', sans-serif;">Antrian</h2>
                <img src="/img/icon/b.png" alt="Tiket B" class="w-20 h-20 px-4 py-4 " style="width: 100px; height: 100px;">
            </button>
        </form>

        <form id="ticket-form-r" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('R')">
            @csrf
            <input type="hidden" name="queue_type" value="R">
            <button type="submit" class="block w-64 h-64 bg-white mx-6 p-6 rounded-lg shadow-md shadow-black hover:shadow-lg hover:bg-gray-400 transition duration-300 cursor-pointer flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold mb-6 p-2" style="font-family: 'Urbanist', sans-serif;">Antrian</h2>
                <img src="/img/icon/r.png" alt="Tiket R" class="w-20 h-20 px-4 py-4" style="width: 100px; height: 100px;">
            </button>
        </form>
    </div>

    <!-- Clear All Tickets Button -->
    <form id="clear-tickets-form" action="{{ route('tickets.clear') }}" method="POST" onsubmit="return confirmClearTickets(event)">
        @csrf
        <button type="submit" class="px-16 py-3 mt-6 bg-red-600 text-white text-xl font-semibold rounded-lg shadow-md shadow-black hover:bg-red-500 transition duration-300" style="font-family: 'Urbanist', sans-serif;">
            Clear All Tickets
        </button>
    </form>
</div>

<!-- Background Blur -->
<div id="blur-overlay" class="fixed inset-0 backdrop-blur-md hidden"></div>

<!-- Confirmation Box for Generating Tickets (Unchanged) -->
<div id="confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <!-- Loading Animation -->
    <div id="loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>

    <!-- Confirmation Content (Hidden Initially) -->
    <div id="confirm-content" class="hidden text-center">
        <h2 class="text-lg font-bold mb-2" style="font-family: 'Urbanist', sans-serif;">Buat Tiket?</h2>
        <p class="mb-4" style="font-family: 'Urbanist', sans-serif;">Apakah anda yakin ingin membuat tiket untuk <span id="queue-type" class="font-semibold"></span>?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">Batal</button>
            <button onclick="proceedToGenerate()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">Buat Tiket</button>
        </div>
    </div>
</div>

<!-- Confirmation Box for Clearing Tickets -->
<div id="clear-confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <!-- Loading Animation -->
    <div id="clear-loading-animation" class="flex justify-center items-center hidden">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>

    <!-- Confirmation Content -->
    <div id="clear-confirm-content" class="text-center">
        <h2 class="text-lg font-bold mb-2" style="font-family: 'Urbanist', sans-serif;">Hapus Tiket?</h2>
        <p class="mb-4" style="font-family: 'Urbanist', sans-serif;">Apakah anda yakin ingin menghapus semua tiket?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="closeClearModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">Batal</button>
            <button onclick="proceedToClear()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">Ya</button>
        </div>
    </div>
</div>

<script>
    // Confirmation for Generating Tickets (Unchanged)
    function confirmTicket(queueType) {
        event.preventDefault(); // Prevent form submission
        document.getElementById('queue-type').textContent = queueType;
        document.getElementById('blur-overlay').classList.remove('hidden');
        document.getElementById('confirm-box').classList.remove('hidden');
        document.getElementById('confirm-content').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('blur-overlay').classList.add('hidden');
        document.getElementById('confirm-box').classList.add('hidden');
        document.getElementById('confirm-content').classList.add('hidden');
    }

    function proceedToGenerate() {
        document.getElementById(`ticket-form-${document.getElementById('queue-type').textContent}`).submit();
    }

    // Confirmation for Clearing Tickets
    function confirmClearTickets(event) {
        event.preventDefault(); // Prevent form submission
        document.getElementById('blur-overlay').classList.remove('hidden');
        document.getElementById('clear-confirm-box').classList.remove('hidden');
    }

    function closeClearModal() {
        document.getElementById('blur-overlay').classList.add('hidden');
        document.getElementById('clear-confirm-box').classList.add('hidden');
    }

    function proceedToClear() {
        // Show loading animation
        document.getElementById('clear-loading-animation').classList.remove('hidden');
        document.getElementById('clear-confirm-content').classList.add('hidden');

        // Simulate a delay for the loading animation (replace with actual form submission logic)
        setTimeout(() => {
            document.getElementById('clear-tickets-form').submit();
        }, 2000); // 2 seconds delay for demonstration
    }
</script>
@endsection