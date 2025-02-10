<nav class="fixed top-0 left-0 w-full flex items-center justify-between px-4 py-3 text-white z-50" >
    <!-- Left Container (Logo) -->
    <div class="flex items-center pl-4">
        <a href="{{ url('/') }}" class="text-xl font-bold">
            <img src="{{ asset('img/logo.png') }}" alt="RS Logo" class="h-[80px] w-[300px]">
        </a>
    </div>

    

    <!-- Right Container (Date & Time with Background) -->
    <div class="flex justify-end pr-4">
    <div class="flex py-2">
        <a href="{{ url('/tickets/generate') }}">
        <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-gray-500 transition duration-300 cursor-pointer" style="margin-right: 25px;">
            <img src="{{ asset('img/icon/plus.png') }}" alt="Icon" class="w-6 h-6">
        </button>
        </a>

        <a href="{{ url('/tickets/queue') }}">
            <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-gray-500 transition duration-300 cursor-pointer" style="margin-right: 50px;">
                <img src="{{ asset('img/icon/queue.png') }}" alt="Icon" class="w-6 h-6">
            </button>
        </a>






    
    </div>

        <div class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md">
            <span id="date" class="block" style="font-family: 'Urbanist', sans-serif;"></span>
            <span id="time" class="block" style="font-family: 'Urbanist', sans-serif;"></span>
        </div>
    </div>
</nav>

<script>
    function updateDateTime() {
        const now = new Date();
        const day = now.getDate();
        const month = now.toLocaleString('default', { month: 'long' });
        const year = now.getFullYear();
        document.getElementById('date').innerText = `${day} ${month} ${year}`;
        document.getElementById('time').innerText = now.toLocaleTimeString('en-GB');
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Dropdown functionality
    document.getElementById('dropdownButton').addEventListener('click', function () {
        document.getElementById('dropdownMenu').classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!document.getElementById('dropdownButton').contains(event.target) && 
            !document.getElementById('dropdownMenu').contains(event.target)) {
            document.getElementById('dropdownMenu').classList.add('hidden');
        }
    });
</script>
