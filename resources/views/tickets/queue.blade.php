@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Left Column: Current Queue Display and Button -->
    <div class="flex flex-col items-center justify-center w-1/2">
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
        <form action="{{ route('tickets.next') }}" method="POST" class="w-full max-w-md">
            @csrf
            <button type="submit" class="w-full px-32 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md hover:bg-green-500 transition duration-300" style="font-family: 'Urbanist', sans-serif;">
                Panggil Antrian Berikutnya
            </button>
        </form>
    </div>

    <!-- Right Column: List of Pending or Active Tickets -->
    <div class="w-1/2 bg-white p-8">
        <h2 class="text-3xl font-semibold text-gray-700 mb-4" style="font-family: 'Urbanist', sans-serif;">
            Daftar Antrian
        </h2>
        <ul class="space-y-4">
            @forelse ($pendingTickets as $ticket)
                <li class="text-2xl text-gray-600 bg-gray-50 p-4 rounded-lg shadow-sm" style="font-family: 'Urbanist', sans-serif;">
                    <form action="{{ route('tickets.setCurrent', $ticket->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:underline">
                            Nomor Antrian: {{ $ticket->ticket_number }}
                        </button>
                    </form>
                </li>
            @empty
                <li class="text-2xl text-gray-600" style="font-family: 'Urbanist', sans-serif;">
                    Tidak ada antrian yang tertunda.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection