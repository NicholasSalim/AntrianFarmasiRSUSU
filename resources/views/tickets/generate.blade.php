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

    <!-- Ticket Generation Buttons -->
    <div class="space-y-4 my-6">
        <form id="ticket-form-a" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('A')">
            @csrf
            <input type="hidden" name="queue_type" value="A">
            <button type="submit" class="px-16 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg shadow-md shadow-black hover:bg-blue-500 transition duration-300">
                Buat Tiket - Tipe A
            </button>
        </form>

        <form id="ticket-form-b" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('B')">
            @csrf
            <input type="hidden" name="queue_type" value="B">
            <button type="submit" class="px-16 py-3 bg-green-600 text-white text-lg font-semibold rounded-lg shadow-md shadow-black hover:bg-green-500 transition duration-300">
                Buat Tiket - Tipe B
            </button>
        </form>

        <form id="ticket-form-r" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('R')">
            @csrf
            <input type="hidden" name="queue_type" value="R">
            <button type="submit" class="px-16 py-3 bg-red-600 text-white text-lg font-semibold rounded-lg shadow-md shadow-black hover:bg-red-500 transition duration-300">
                Buat Tiket - Tipe R
            </button>
        </form>
    </div>
</div>

<!-- Background Blur -->
<div id="blur-overlay" class="fixed inset-0  backdrop-blur-md hidden"></div>

<!-- Confirmation Box (With Loading Animation) -->
<div id="confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity">
    <!-- Loading Animation -->
    <div id="loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
    </div>

    <!-- Confirmation Content (Hidden Initially) -->
    <div id="confirm-content" class="hidden text-center">
        <h2 class="text-lg font-bold mb-2"style="font-family: 'Urbanist', sans-serif;">Buat Tiket?</h2>
        <p class="mb-4"style="font-family: 'Urbanist', sans-serif;">Apakah anda yakin ingin membuat tiket untuk <span id="queue-type" class="font-semibold"></span>?</p>
        <div class="flex justify-center space-x-4">
           
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400" style="font-family: 'Urbanist', sans-serif;">Batal</button>
            <button onclick="proceedToGenerate()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500" style="font-family: 'Urbanist', sans-serif;" >Buat Tiket</button>

        </div>
    </div>
</div>

<script>
    let selectedQueueType = '';

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
</script>

@endsection
