 

    document.getElementById('openChangePasswordModal').addEventListener('click', function () {
        // Show unique blur overlay & modal
        document.getElementById('blur-overlay-password').classList.remove('hidden');
        document.getElementById('changePasswordModalContainer').classList.remove('hidden');

        // Show unique loading animation & hide form
        document.getElementById('password-loading-spinner').classList.remove('hidden');
        document.getElementById('password-form-wrapper').classList.add('hidden');

        // Simulate loading delay before showing form
        setTimeout(() => {
            document.getElementById('password-loading-spinner').classList.add('hidden'); // Hide spinner
            document.getElementById('password-form-wrapper').classList.remove('hidden'); // Show form
        }, 500); // Matches your alert animation delay
    });

    function closePasswordModal() {
        document.getElementById('blur-overlay-password').classList.add('hidden');
        document.getElementById('changePasswordModalContainer').classList.add('hidden');
    }