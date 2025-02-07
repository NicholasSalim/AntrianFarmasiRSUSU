@extends('layouts.app2')

@section('content')

<div class="flex flex-col items-center justify-center min-h-screen px-4"
     style="padding-top: 150px; padding-bottom:100px">  
    <!-- Ticket Container -->
    <div class="bg-white rounded-3xl shadow-lg p-8 w-full max-w-md text-center" id="ticketPrint">
        <!-- Ticket Title -->
        <h1 class="hospital-name text-2xl font-bold text-gray-800 ">
        Rumah Sakit Prof. Dr. Chairuddin P. Lubis
        </h1>

        <!-- Separator -->
        <div class="border-t border-dashed border-gray-400 my-6"></div>

        <!-- Date & Time Section -->
        <div class="date-time flex justify-between w-full text-sm text-gray-600 mb-6 my-2">
            <p>{{ now()->timezone('Asia/Jakarta')->translatedFormat('j F Y') }}</p>
            <p>{{ now()->timezone('Asia/Jakarta')->format('H:i:s') }}</p>
        </div>

        <!-- Ticket Details -->
        <div class="ticket-info text-lg text-gray-700 space-y-3">
            <p> Nomor Antrian Anda </p>
            <strong class="text-5xl">{{ $ticket->ticket_number }}</strong>
        </div>

        <p class="text-gray-500 mt-12">Mohon Tunggu Nomor Anda Dipanggil</p>
        <p class="text-gray-500 italic my-4">Terima kasih sudah mengantri!</p>

        <!-- Print Button -->
        <button onclick="window.print()" 
            class="my-6 w-full px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-500 transition duration-300 cursor-pointer">
            Cetak Tiket
        </button>
    </div>

    <!-- Buttons (Outside Ticket Container, Aligned to Edges) -->
    <div class="flex justify-between w-full max-w-md mt-4">
        <!-- Back Button (Left) -->
        <a href="/tickets/generate" 
            class="px-6 py-3 mt-12 bg-gray-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-gray-500 transition duration-300 text-center w-1/2 cursor-pointer">
            Kembali
        </a>

        <!-- Cek Antrian Button (Right) -->
        <a href="/tickets/queue" 
            class="px-6 py-3 mt-12  bg-green-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-green-500 transition duration-300 text-center w-1/2 ml-2 cursor-pointer">
            Cek Antrian
        </a>
    </div>

</div>

@endsection
