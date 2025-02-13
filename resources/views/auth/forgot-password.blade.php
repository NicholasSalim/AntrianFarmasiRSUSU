<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    @vite(['public/css/output.css'])
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex flex-col items-center justify-center w-full max-w-md p-12 bg-white rounded-3xl shadow-lg">
        <h1 class="text-3xl font-bold mb-6" style="font-family: 'Urbanist', sans-serif;">Forgot Password</h1>
        
<!-- Success Message -->
@if (session('success'))
    <div id="successBox" class="w-full bg-green-200 text-green-700 px-4 py-3 rounded-lg flex justify-between items-center mb-4 border border-green-500">
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button type="button" class="text-green-700 text-lg font-bold ml-2 cursor-pointer" onclick="document.getElementById('successBox').remove();">
            &times;
        </button>
    </div>
@endif

<!-- Error Messages -->
@if ($errors->has('email'))
    <div id="errorBox" class="w-full bg-red-200 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center mb-4 border border-red-500">
        <span class="text-sm font-medium">
            @foreach ($errors->get('email') as $error)
                {{ $error }}<br>
            @endforeach
        </span>
        <button type="button" class="text-red-700 text-lg font-bold ml-2 cursor-pointer" onclick="document.getElementById('errorBox').remove();">
            &times;
        </button>
    </div>
@endif





                <!-- Send Code Form -->
                <form action="{{ url('/forgot-password/send-code') }}" method="POST" class="w-full"  style="font-family: 'Urbanist', sans-serif;">
                    @csrf
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-2 border rounded-lg">
                    <button type="submit" class="text-blue-500 hover-underline mt-2 mb-6 cursor-pointer">Send Verification Code</button>
                </form>



                <!-- Verification Code Input & Button -->
                <div class="w-full"  style="font-family: 'Urbanist', sans-serif;"> 
                    <label class="block text-gray-700">Enter Verification Code</label>
                    <input type="text" id="verification_code" class="w-full px-4 py-2 border rounded-lg">
                    <button id="verify-button" class="w-full bg-green-500 text-white my-4 py-2 rounded-lg hover:bg-green-600 cursor-pointer transition">Verify Code</button>
                    <p id="error-message" class="text-red-500 hidden mb-4"></p>
                    <a href="{{ url('/login') }}">
                    <button class="px-4 py-2 mt-4 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">Kembali</button>
                    </a>
                </div>


<!-- Password Reset Modal -->
<div id="reset-password-modal" class="fixed inset-0 backdrop-blur-md hidden flex justify-center items-center 
    @if (session('modalOpen') || $errors->has('password') || $errors->has('password_confirmation')) @else hidden @endif">
    <div class="bg-white p-12 rounded-lg shadow-lg w-full max-w-md" style="font-family: 'Urbanist', sans-serif;">
        <h2 class="text-xl font-bold mb-4">Reset Password</h2>

        <form id="reset-password-form" action="{{ url('/forgot-password/reset-password') }}" method="POST">
            @csrf

            <!-- New Password Input -->
            <label class="block text-gray-700">New Password</label>
            <div class="relative w-full">
                <input type="password" name="password" id="new-password" required
                    class="w-full px-4 py-2 border rounded-lg pr-10 @error('password') border-red-500 @enderror">
                <img src="{{ asset('img/icon/hide.png') }}" id="toggleNewPasswordIcon"
                    class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
                    onclick="togglePasswordVisibility('new-password', 'toggleNewPasswordIcon')"
                    data-show="{{ asset('img/icon/show.png') }}"
                    data-hide="{{ asset('img/icon/hide.png') }}">
            </div>

            <!-- Show specific password errors under the New Password field -->
            @foreach ($errors->get('password') as $message)
                @if (str_contains($message, 'at least one number'))
                    <p class="text-red-500 text-sm mt-1">The password must contain at least one number.</p>
                @elseif (str_contains($message, 'at least one special character'))
                    <p class="text-red-500 text-sm mt-1">The password must contain at least one special character.</p>
                @elseif (!str_contains($message, 'confirmation')) {{-- Ignore confirmation error here --}}
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @endif
            @endforeach

            <!-- Confirm Password Input -->
            <label class="block text-gray-700 mt-2">Confirm Password</label>
            <div class="relative w-full">
                <input type="password" name="password_confirmation" id="confirm-password" required
                    class="w-full px-4 py-2 border rounded-lg pr-10 @error('password_confirmation') border-red-500 @enderror">
                <img src="{{ asset('img/icon/hide.png') }}" id="toggleConfirmPasswordIcon"
                    class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
                    onclick="togglePasswordVisibility('confirm-password', 'toggleConfirmPasswordIcon')"
                    data-show="{{ asset('img/icon/show.png') }}"
                    data-hide="{{ asset('img/icon/hide.png') }}">
            </div>

            <!-- Show password confirmation error under the Confirm Password field -->
            @foreach ($errors->get('password') as $message)
                @if (str_contains($message, 'confirmation'))
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @endif
            @endforeach

            @foreach ($errors->get('password_confirmation') as $message)
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @endforeach

            <!-- Submit Button & Loading Animation -->
            <div class="relative mt-4">
                <button type="submit" id="reset-password-button" class="w-full bg-green-500 text-white my-4 py-2 rounded-lg hover:bg-green-600 cursor-pointer transition">
                    Reset Password
                </button>

                <!-- Loading Animation (Hidden Initially) -->
                <div id="loading-animation" class="hidden absolute inset-0 flex justify-center items-center bg-white bg-opacity-50">
                    <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
                </div>
            </div>
        </form>

        <button id="close-modal" class="w-full px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">
            Close
        </button>
    </div>
</div>




<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Close modal when clicking the close button
        document.getElementById("close-modal").addEventListener("click", function () {
            document.getElementById("reset-password-modal").classList.add("hidden");
        });

        // Keep modal open only for password validation errors
        if (@json(session('modalOpen')) || @json($errors->has('password')) || @json($errors->has('password_confirmation'))) {
            document.getElementById("reset-password-modal").classList.remove("hidden");
        }

        // Show loading animation when submitting password reset form
        document.getElementById("reset-password-form").addEventListener("submit", function () {
            document.getElementById("reset-password-button").classList.add("opacity-50", "cursor-not-allowed");
            document.getElementById("reset-password-button").disabled = true;
            document.getElementById("loading-animation").classList.remove("hidden");
        });

        // Attach event listener to verify button
        document.getElementById("verify-button").addEventListener("click", function () {
            verifyCode();
        });
    });

    function verifyCode() {
        let code = document.getElementById("verification_code").value;

        if (!code) {
            document.getElementById("error-message").textContent = "Please enter the verification code.";
            document.getElementById("error-message").classList.remove("hidden");
            return;
        }

        fetch("{{ url('/forgot-password/verify-code') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("error-message").classList.add("hidden");

                // Show modal for password reset after code verification
                document.getElementById("reset-password-modal").classList.remove("hidden");
                document.getElementById("reset-code").value = code;
            } else {
                document.getElementById("error-message").textContent = "Invalid code. Please try again.";
                document.getElementById("error-message").classList.remove("hidden");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("error-message").textContent = "";
            document.getElementById("error-message").classList.remove("hidden");
        });
    }


    function togglePasswordVisibility(inputId, iconId) {
        let passwordInput = document.getElementById(inputId);
        let toggleIcon = document.getElementById(iconId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.src = toggleIcon.getAttribute("data-show"); // Switch to show icon
        } else {
            passwordInput.type = "password";
            toggleIcon.src = toggleIcon.getAttribute("data-hide"); // Switch back to hide icon
        }
    }
</script>



</body>
</html>
