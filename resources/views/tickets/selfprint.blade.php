@extends('layouts.app2')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center" style="margin-top:50px;">
    <!-- Header -->
    <h1 class="text-7xl font-bold text-white mb-6" style="font-family: 'Urbanist', sans-serif;">
        Ambil Antrian
    </h1>
    <!-- Caption -->
    <p class="text-2xl text-white mb-12" style="font-family: 'Urbanist', sans-serif;">
        Pilih jenis antrian yang ingin diambil
    </p>



                <!-- Ticket Generation Cards -->
                <div class="container mx-auto flex justify-center space-x-6 my-6" style="font-family: 'Urbanist', sans-serif; ">

                @php
                $ticketDescriptions = [
                    'A' => 'Universal',
                    'B' => 'Khusus Poli Geriatri, Poli TB, Poli Sibayak dan Pasien Umum',
                    'R' => 'Khusus Racikan'
                ];
                @endphp

                    @foreach (['A', 'B', 'R'] as $type)
                        @php
                            $lastTicket = $lastTickets[$type] ?? null;
                        @endphp
                        <form id="ticket-form-{{ strtolower($type) }}" action="{{ route('ticket.generate') }}" method="POST" onsubmit="return confirmTicket('{{ $type }}')">
                            @csrf
                            <input type="hidden" name="queue_type" value="{{ $type }}">
                            <button type="submit" class="block w-64 h-64 bg-white mx-6 p-6 rounded-3xl shadow-md shadow-black hover:shadow-lg hover:bg-gray-400 transition duration-300 cursor-pointer flex flex-col items-center justify-center" style="width: 300px; height: 400px;">
                                <h2 class="text-xl font-semibold mb-6 p-2" style="font-family: 'Urbanist', sans-serif;">Antrian</h2>
                                <img src="/img/icon/{{ strtolower($type) }}.png" alt="Tiket {{ $type }}" class="w-20 h-20 px-4 py-4" style="width: 100px; height: 100px;">
                                
                                        <!-- Informational Message -->
                                    <div class="text-gray-600 text-lg mt-4 text-center">
                                    <p class="font-light mt-2">{{ $ticketDescriptions[$type] }}</p>
                                    </div>


                                <div class="mt-4 text-gray-600 text-lg">
                                    @if ($lastTicket)
                                        <p class="font-bold mt-2">Tiket terakhir dibuat: {{ $lastTicket->ticket_number }}</p>
                                        <p class="text-sm">Waktu dibuat: {{ \Carbon\Carbon::parse($lastTicket->created_at)->format('H:i') }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">No tickets yet</p>
                                    @endif
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>

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
        }, 250); // 2 seconds delay for demonstration
    }
</script>

@endsection