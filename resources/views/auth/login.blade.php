<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Operator Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    @vite(['public/css/output.css'])
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex flex-col items-center justify-center w-full max-w-md p-12 bg-white rounded-3xl shadow-lg">
        <h1 class="text-3xl font-bold mb-6" style="font-family: 'Urbanist', sans-serif;">Antrian Farmasi</h1>

        <form action="{{ url('/login') }}" method="POST" class="w-full">
            @csrf

            <!-- Invalid Credentials Error -->
            @if ($errors->has('invalid'))
                <div id="errorBox" class="bg-red-200 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center mb-4">
                    <span class="text-sm font-medium">{{ $errors->first('invalid') }}</span>
                    <button type="button" class="text-red-700 text-lg font-bold ml-2 cursor-pointer" onclick="closeErrorBox()">
                        &times;
                    </button>
                </div>
            @endif

            <!-- Email Input -->
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input with Toggle -->
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 pr-10">
                    <img src="{{ asset('img/icon/hide.png') }}" id="togglePasswordIcon"
                        class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
                        onclick="togglePasswordVisibility()"
                        data-show="{{ asset('img/icon/show.png') }}"
                        data-hide="{{ asset('img/icon/hide.png') }}">
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-green-500 text-white my-4 py-2 rounded-lg hover:bg-green-600 cursor-pointer transition">
                Login
            </button>

            <a href="{{ url('/forgot-password') }}" class="block text-right text-blue-500 mt-2 hover-underline">
                Forgot Password?
            </a>
        </form>
    </div>
</body>

<!-- JavaScript for Toggling Password Visibility -->
<script>
    function togglePasswordVisibility() {
        let passwordInput = document.getElementById("password");
        let toggleIcon = document.getElementById("togglePasswordIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.src = toggleIcon.getAttribute("data-show"); // Show icon
        } else {
            passwordInput.type = "password";
            toggleIcon.src = toggleIcon.getAttribute("data-hide"); // Hide icon
        }
    }

    function closeErrorBox() {
        var errorBox = document.getElementById("errorBox");
        if (errorBox) {
            errorBox.style.display = "none";
        }
    }
</script>

</html>
