//This file will contain the validation functions for the form inputs
// contact.php
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');

    form.addEventListener('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });
});

function validateForm() {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const subject = document.getElementById('subject');
    const message = document.getElementById('message');

    // Name validation
    if (name.value.trim() === '') {
        alert('Please enter your name.');
        name.focus();
        return false;
    }

    // Email validation
    if (!validateEmail(email.value.trim())) {
        alert('Please enter a valid email address.');
        email.focus();
        return false;
    }

    // Subject is optional - no validation here

    // Message validation
    if (message.value.trim() === '') {
        alert('Please enter your message.');
        message.focus();
        return false;
    }

    return true; // If all validations pass
}

function validateEmail(email) {
    // Simple email regex pattern
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
