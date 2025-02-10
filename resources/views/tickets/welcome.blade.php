@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center">
    <!-- Header -->
    <h1 class="text-7xl font-bold text-white mb-6" style="font-family: 'Urbanist', sans-serif;">
        Selamat Datang
    </h1>
    <!-- Caption -->
    <p class="text-2xl text-white my-6" style="font-family: 'Urbanist', sans-serif;">
        Farmasi Rumah Sakit Prof. Dr. Chairuddin P. Lubis
    </p>

    <div class="container mx-auto flex flex-row justify-center space-x-6 mt-6">
        <!-- Column 1: Buat Ticket -->
        <div class="w-1/3 mx-6">
            <a href="/tickets/generate" class="block">
                <div class="bg-white p-6 rounded-lg shadow-md shadow-black hover:shadow-lg transition duration-300 hover:bg-gray-400 cursor-pointer flex flex-col items-center">
                    <img src="/img/icon/add.png" alt="Buat Ticket" class="w-20 h-20 mb-4">
                    <h2 class="text-xl font-semibold" style="font-family: 'Urbanist', sans-serif;">Buat Ticket</h2>
                </div>
            </a>
        </div>


        <!-- Column 3: Kelola Antrian -->
        <div class="w-1/3 mx-6">
            <a href="/tickets/queue" class="block">
                <div class="bg-white p-6 rounded-lg shadow-md shadow-black hover:shadow-lg transition duration-300 hover:bg-gray-400 cursor-pointer flex flex-col items-center">
                    <img src="/img/icon/queue2.png" alt="Kelola Antrian" class="w-20 h-20 mb-4">
                    <h2 class="text-xl font-semibold" style="font-family: 'Urbanist', sans-serif;">Kelola Antrian</h2>
                </div>
            </a>
        </div>
        
        
        
        <!-- Column 3: Lihat Antrian -->
        <div class="w-1/3 mx-6">
            <a href="/tickets/display" class="block">
                <div class="bg-white p-6 rounded-lg shadow-md shadow-black hover:shadow-lg transition duration-300 hover:bg-gray-400 cursor-pointer flex flex-col items-center">
                    <img src="/img/icon/eye2.png" alt="Lihat Antrian" class="w-20 h-20 mb-4">
                    <h2 class="text-xl font-semibold" style="font-family: 'Urbanist', sans-serif;">Lihat Antrian</h2>
                </div>
            </a>
        </div>

        
    </div>
</div>
@endsection
