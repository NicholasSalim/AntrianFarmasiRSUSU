@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center">
    <!-- Header -->
    <h1 class="text-7xl font-bold text-white mb-6" style="font-family: 'Urbanist', sans-serif;">
    Selamat Datang
    </h1>
    <!-- Caption -->
    <p class="text-2xl text-white my-6" style="font-family: 'Urbanist', sans-serif;">Farmasi Rumah Sakit Prof. Dr. Chairuddin P. lubis</p>

    <!-- Button -->
    <a href="/tickets/generate">
    <button class="mt-6 px-32 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md shadow-black hover:bg-green-500 transition duration-300" style="font-family: 'Urbanist', sans-serif;">
    Buat Ticket
</button>

    <!-- Button: Status -->
    <a href="/tickets/queue">
        <button class="my-6 px-32 py-5 bg-blue-600 text-white text-2xl font-semibold rounded-lg shadow-md shadow-black hover:bg-blue-500 transition duration-300" style="font-family: 'Urbanist', sans-serif;">
            Status
        </button>
    </a>
</div>
@endsection
