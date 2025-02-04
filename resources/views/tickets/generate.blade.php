@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center">
    <!-- Header -->
    <h1 class="text-7xl font-bold text-white mb-6" style="font-family: 'Urbanist', sans-serif;">
    Ambil Antrian
    </h1>
    <!-- Caption -->
    <p class="text-2xl text-white mb-12" style="font-family: 'Urbanist', sans-serif;">Pilih Jenis Antrian yang ingin anda ambil</p>

  
</div>
    <h1>Generate a Queue Ticket</h1>
    <form action="{{ route('ticket.generate') }}" method="POST">
        @csrf
        <button type="submit">Generate Ticket</button>
    </form>

@endsection