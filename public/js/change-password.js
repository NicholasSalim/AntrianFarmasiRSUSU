 
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
        // Hide modal and blur overlay
        document.getElementById('blur-overlay-password').classList.add('hidden');
        document.getElementById('changePasswordModalContainer').classList.add('hidden');
    
        // Clear error messages
        document.querySelectorAll('.text-red-500').forEach(error => error.remove());
    
        // Reset input borders (remove red border class)
        document.querySelectorAll('.border-red-500').forEach(input => input.classList.remove('border-red-500'));
    
        // Clear input values
        document.getElementById('old_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_password').value = '';
    }
    


    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let icon = document.getElementById(fieldId + "_icon");

        // Get image paths from data attributes
        let showIcon = icon.getAttribute("data-show");
        let hideIcon = icon.getAttribute("data-hide");

        if (field.type === "password") {
            field.type = "text";
            icon.src = showIcon; // Change to "show" icon
        } else {
            field.type = "password";
            icon.src = hideIcon; // Change to "hide" icon
        }
    }


    
    document.getElementById("changePasswordForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent actual form submission
    
        // Show blur overlay and confirmation box
        document.getElementById("password-blur-overlay").classList.remove("hidden");
        document.getElementById("password-confirm-box").classList.remove("hidden");
    
        // Show loading animation
        document.getElementById("password-loading-animation").classList.remove("hidden");
        document.getElementById("password-confirm-content").classList.add("hidden");
    
        // Wait 1 second, then show confirmation message
        setTimeout(() => {
            document.getElementById("password-loading-animation").classList.add("hidden"); // Hide loading
            document.getElementById("password-confirm-content").classList.remove("hidden"); // Show confirmation
        }, 500);
    });
    
    function proceedWithPasswordChange() {
        // Show loading spinner again
        document.getElementById("password-loading-animation").classList.remove("hidden");
        document.getElementById("password-confirm-content").classList.add("hidden");
    
        // Wait 1.5 seconds, then submit form
        setTimeout(() => {
            document.getElementById("changePasswordForm").submit();
        }, 500);
    }
    
    function closePasswordAlertModal() {
        // Hide blur and confirmation modal
        document.getElementById("password-blur-overlay").classList.add("hidden");
        document.getElementById("password-confirm-box").classList.add("hidden");
    }
    