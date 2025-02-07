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
    <button class="mt-6 px-24 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md shadow-black hover:bg-green-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
    Buat Ticket
</button>

    <!-- Button: Status -->
    <a href="/tickets/display">
        <button class="mt-6 px-24 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md shadow-black hover:bg-green-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
            Lihat Antrian
        </button>
    </a>

    <!-- Button: Status -->
    <a href="/tickets/queue">
        <button class="mt-6 px-24 py-5 bg-green-600 text-white text-2xl font-semibold rounded-lg shadow-md shadow-black hover:bg-green-500 transition duration-300 cursor-pointer" style="font-family: 'Urbanist', sans-serif;">
            Kelola Antrian
        </button>
    </a>

    
</div>
@endsection
