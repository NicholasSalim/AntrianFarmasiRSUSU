//dropdown
document.getElementById('profileDropdownButton').addEventListener('click', function () {
    document.getElementById('profileDropdownMenu').classList.toggle('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', function (event) {
    if (!document.getElementById('profileDropdownButton').contains(event.target) && 
        !document.getElementById('profileDropdownMenu').contains(event.target)) {
        document.getElementById('profileDropdownMenu').classList.add('hidden');
    }
});

//time
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
document.getElementById('dropdownButton')?.addEventListener('click', function () {
    document.getElementById('dropdownMenu').classList.toggle('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', function (event) {
    if (!document.getElementById('dropdownButton')?.contains(event.target) &&
        !document.getElementById('dropdownMenu')?.contains(event.target)) {
        document.getElementById('dropdownMenu')?.classList.add('hidden');
    }
});