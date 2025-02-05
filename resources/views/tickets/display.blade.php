@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center bg-gray-100">
    <!-- Header -->
    <h1 class="text-5xl font-bold text-gray-800 mb-8" style="font-family: 'Urbanist', sans-serif;">
        Antrian Farmasi
    </h1>

    <!-- Current Queue Display -->
    <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
        <h2 class="text-3xl font-semibold text-gray-700 mb-4" style="font-family: 'Urbanist', sans-serif;">
            Nomor Antrian Saat Ini
        </h2>
        <p class="text-6xl font-bold text-green-600" style="font-family: 'Urbanist', sans-serif;">
            {{ $currentTicket->ticket_number ?? 'Tidak ada antrian' }}
        </p>
    </div>

    <!-- Button: Next Ticket -->
    <form action="{{ route('tickets.next') }}" method="POST" class="w-full">
        @csrf
        <button type="submit" class="px-32 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md hover:bg-green-500 transition duration-300" style="font-family: 'Urbanist', sans-serif;">
            Panggil Antrian Berikutnya
        </button>
    </form>
</div>
@endsection