<nav class="fixed top-0 left-0 w-full flex items-center justify-between px-4 py-3 text-white z-50">
    <!-- Left Container (Logo) -->
    <div class="flex items-center pl-4">
        <a href="{{ url('/home') }}" class="text-xl font-bold">
            <img src="{{ asset('img/logo.png') }}" alt="RS Logo" class="h-[80px] w-[300px]">
        </a>
    </div>

    <!-- Right Container (Buttons + Date & Time) -->
    <div class="flex justify-end pr-4">
        <div class="flex py-2">
            <a href="{{ url('/tickets/generate') }}">
                <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-gray-500 transition duration-300 cursor-pointer" style="margin-right: 25px;">
                    <img src="{{ asset('img/icon/plus.png') }}" alt="Icon" class="w-6 h-6">
                </button>
            </a>

            <a href="{{ url('/tickets/queue') }}">
                <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-gray-500 transition duration-300 cursor-pointer" style="margin-right: 25px;">
                    <img src="{{ asset('img/icon/queue.png') }}" alt="Icon" class="w-6 h-6">
                </button>
            </a>

            <a href="{{ url('/tickets/display') }}">
                <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-gray-500 transition duration-300 cursor-pointer" style="margin-right: 50px;">
                    <img src="{{ asset('img/icon/eye.png') }}" alt="Icon" class="w-6 h-6">
                </button>
            </a>

            <!-- User Dropdown Menu -->
            <div class="relative" >
                @auth
                    <!-- Profile Button -->
                    <button id="profileDropdownButton" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-400 cursor-pointer" style="margin-right: 50px;">
                        <img src="{{ asset('img/icon/user.png') }}" alt="User Profile" class="w-8 h-8 rounded-full">
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdownMenu" class="absolute right-0 mt-2 bg-white text-black rounded-lg shadow-lg hidden overflow-hidden">
                        <a href="#" id="openChangePasswordModal" class="flex items-center justify-center px-6 py-3 text-sm font-medium transition duration-300 cursor-pointer rounded-t-lg hover:bg-gray-200" style="font-family: 'Urbanist', sans-serif;">
                            <img src="{{ asset('img/icon/padlock.png') }}" alt="Lock Icon" class="w-5 h-5 mr-2"> 
                            Change Password
                        </a>

                        <hr class="border-gray-300 mx-4">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center justify-center px-6 py-3 text-sm font-medium transition duration-300 cursor-pointer w-full rounded-b-lg hover:bg-gray-200" style="font-family: 'Urbanist', sans-serif;">
                                <img src="{{ asset('img/icon/exit.png') }}" alt="Logout Icon" class="w-5 h-5 mr-2"> 
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Date & Time -->
        <div class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md ml-4">
            <span id="date" class="block" style="font-family: 'Urbanist', sans-serif;"></span>
            <span id="time" class="block" style="font-family: 'Urbanist', sans-serif;"></span>
        </div>
    </div>
</nav>


<!-- 🔹 Unique Blur Overlay -->
<div id="blur-overlay-password" class="fixed inset-0 backdrop-blur-md bg-gray-900 bg-opacity-30 hidden z-40 transition-opacity duration-300"></div>

<!-- 🔹 Change Password Modal -->
<div id="changePasswordModalContainer" class="fixed inset-0 flex items-center justify-center hidden z-50 transition-opacity duration-300" style="font-family: 'Urbanist', sans-serif;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
        

        <!-- 🔹 Unique Loading Animation -->
        <div id="password-loading-spinner" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-600 border-solid"></div>
        </div>


                        <!-- 🔹 Password Change Form -->
                <div id="password-form-wrapper" class="@if ($errors->any()) block @else hidden @endif">
                    <h2 class="text-2xl font-bold mb-4 text-center">Change Password</h2>

                    @if (session('success'))
                        <p class="text-green-600 text-center mb-4">{{ session('success') }}</p>
                    @endif

                    <form id="changePasswordForm" action="{{ route('password.update') }}" method="POST">
                        @csrf
                        
                        <!-- Old Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Old Password</label>
                            <div class="relative">
                                <input type="password" name="old_password" id="old_password"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 pr-8 
                                    @error('old_password') border-red-500 @enderror">
                                <img src="{{ asset('img/icon/hide.png') }}" id="old_password_icon"
                                    class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
                                    onclick="togglePassword('old_password')"
                                    data-show="{{ asset('img/icon/show.png') }}"
                                    data-hide="{{ asset('img/icon/hide.png') }}">
                            </div>
                            @error('old_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

<!-- New Password -->
<div class="mb-4">
    <label class="block text-gray-700">New Password</label>
    <div class="relative">
        <input type="password" name="new_password" id="new_password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 pr-10 
            @error('new_password') border-red-500 @enderror">
        <img src="{{ asset('img/icon/hide.png') }}" id="new_password_icon"
            class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
            onclick="togglePassword('new_password')"
            data-show="{{ asset('img/icon/show.png') }}"
            data-hide="{{ asset('img/icon/hide.png') }}">
    </div>

    <!-- Separate error messages -->
@foreach ($errors->get('new_password') as $message)
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@endforeach

</div>


                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Confirm Password</label>
                            <div class="relative">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 pr-10 
                                    @error('confirm_password') border-red-500 @enderror">
                                <img src="{{ asset('img/icon/hide.png') }}" id="confirm_password_icon"
                                    class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
                                    onclick="togglePassword('confirm_password')"
                                    data-show="{{ asset('img/icon/show.png') }}"
                                    data-hide="{{ asset('img/icon/hide.png') }}">
                            </div>
                            @error('confirm_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition cursor-pointer">
                            Change Password
                        </button>
                        <button type="button" onclick="closePasswordModal()" class="w-full mt-2 py-2 bg-gray-300 rounded-lg text-black hover:bg-gray-400 cursor-pointer">
                            Cancel
                        </button>
                    </form>
                </div>







    </div>
</div>

<!-- Background Blur for Password Change (Ensure it has higher z-index) -->
<div id="password-blur-overlay" class="fixed inset-0 backdrop-blur-md hidden z-50"></div>

<!-- Password Change Confirmation Box (Higher z-index than modal) -->
<div id="password-confirm-box" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg border border-gray-300 hidden transition-opacity z-50">
    <!-- Loading Animation -->
    <div id="password-loading-animation" class="flex justify-center items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-green-600 border-solid"></div>
    </div>

    <!-- Confirmation Content (Hidden Initially) -->
    <div id="password-confirm-content" class="hidden text-center">
        <h2 class="text-lg font-bold mb-2">Change Password?</h2>
        <p class="mb-4">Are you sure you want to change your password?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="closePasswordAlertModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 cursor-pointer">Cancel</button>
            <button onclick="proceedWithPasswordChange()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer">Proceed</button>
        </div>
    </div>
</div>



<script>

    document.addEventListener("DOMContentLoaded", function() {
        @if ($errors->any())
            // Show modal if there are validation errors
            document.getElementById('blur-overlay-password').classList.remove('hidden');
            document.getElementById('changePasswordModalContainer').classList.remove('hidden');
            document.getElementById('password-loading-spinner').classList.add('hidden'); // Hide loading
            document.getElementById('password-form-wrapper').classList.remove('hidden'); // Show form
        @endif
    });

   
</script>

