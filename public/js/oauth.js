/**
 * OAuth Authentication Helper Functions
 * Handles password visibility toggle for login/register forms
 */

/**
 * Toggle password visibility in input field
 * @param {string} inputId - ID of the password input element
 * @param {HTMLElement} button - Button element that was clicked
 */
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (!input || !icon) {
        console.error('Password input or icon not found');
        return;
    }

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/**
 * Handle OAuth errors from redirect
 * Display error messages if OAuth authentication fails
 */
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('oauth_error');

    if (error) {
        const errorMessage = document.createElement('div');
        errorMessage.className = 'bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-center text-sm';
        errorMessage.textContent = decodeURIComponent(error);

        const form = document.querySelector('form');
        if (form) {
            form.parentNode.insertBefore(errorMessage, form);
        }
    }
});
