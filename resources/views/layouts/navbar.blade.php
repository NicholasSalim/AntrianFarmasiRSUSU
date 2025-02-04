<nav class="fixed top-0 left-0 w-full flex items-center justify-between px-4 py-3  text-white z-50">
    <!-- Left Container (Logo) -->
    <div class="flex items-center pl-4">
        <a href="{{ url('/') }}" class="text-xl font-bold">
            <img src="{{ asset('img/logo.png') }}" alt="RS Logo" class="h-[80px] w-[300px]">
        </a>
    </div>

    <!-- Right Container (Date & Time as a Disabled Button) -->
    <!-- Right Container (Date & Time with Background) -->
<div class="flex justify-end pr-4">
    <div class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md">
        <span id="date" class="block"></span>
        <span id="time" class="block"></span>
    </div>
</div>

</nav>



<script>
    function updateDateTime() {
        const now = new Date();

        // Format date as "1 February 2025"
        const day = now.getDate();
        const month = now.toLocaleString('default', { month: 'long' }); // Full month name
        const year = now.getFullYear();
        const formattedDate = `${day} ${month} ${year}`;

        // Format time as "10:21:35"
        const formattedTime = now.toLocaleTimeString('en-GB'); // 24-hour format with seconds

        // Set values
        document.getElementById('date').innerText = formattedDate;
        document.getElementById('time').innerText = formattedTime;
    }

    // Update every second
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
