<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Queue</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    <link href="/css/detail.css" rel="stylesheet">
    
    @vite(['public/css/output.css'])  <!-- ✅ Correct placement -->
</head>


    <body class="bg-cover bg-center bg-no-repeat " style="background-image: url('{{ asset('img/background.png') }}');">
     <!-- Navbar -->
     @include('layouts.navbar2')

    <div class="min-h-screen flex flex-col">
        <!-- Main Content -->
        @yield('content')
    </div>



    
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/ticket-confirmation.js') }}"></script>
<script src="{{ asset('js/ajax-refresh-page.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>
<script src="{{ asset('js/change-password.js') }}"></script>





</html>
