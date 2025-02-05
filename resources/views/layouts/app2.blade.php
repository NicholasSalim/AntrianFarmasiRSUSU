<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Queue</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    <link href="/css/detail.css" rel="stylesheet">
</head>

@vite(['public/css/output.css'])

   
    <body class="bg-cover bg-center bg-no-repeat " style="background-image: url('{{ asset('img/background.png') }}');">
     <!-- Navbar -->
     @include('layouts.navbar')

    <div class="min-h-screen flex flex-col">
        <!-- Main Content -->
        @yield('content')
    </div>



    
</body>
</html>
