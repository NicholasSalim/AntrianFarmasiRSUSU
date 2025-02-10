<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Operator Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex flex-col items-center justify-center w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6">Queue Operator Login</h1>

        @if ($errors->any())
            <div class="text-red-500 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="w-full">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Login
            </button>
        </form>
    </div>
</body>
</html>
