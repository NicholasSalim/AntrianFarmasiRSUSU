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
                        <div class="relative">
                            @auth
                                <!-- Profile Button -->
                                <button id="profileDropdownButton" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-400 cursor-pointer" style="margin-right: 50px;">
                                    <img src="{{ asset('img/icon/user.png') }}" alt="User Profile" class="w-8 h-8 rounded-full">
                                </button>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdownMenu" class="absolute right-0 mt-2 bg-white text-black rounded-lg shadow-lg hidden overflow-hidden">
                            <a href="#" class="flex items-center justify-center px-6 py-3 text-sm font-medium transition duration-300 cursor-pointer rounded-t-lg hover:bg-gray-200 hover:rounded-t-lg" style="font-family: 'Urbanist', sans-serif;">
                                <img src="{{ asset('img/icon/padlock.png') }}" alt="Lock Icon" class="w-5 h-5 mr-2"> 
                                Change Password
                            </a>

                            <hr class="border-gray-300 mx-4">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center justify-center px-6 py-3 text-sm font-medium transition duration-300 cursor-pointer w-full rounded-b-lg hover:bg-gray-200 hover:rounded-b-lg" style="font-family: 'Urbanist', sans-serif;">
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

