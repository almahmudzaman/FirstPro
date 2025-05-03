// Function to validate the registration form
function validateForm() {
    // Get input values and states
    const name = document.getElementById('fullname').value.trim();
    const genderMale = document.getElementById('male').checked;
    const genderFemale = document.getElementById('female').checked;
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('pass').value;
    const confirmPassword = document.getElementById('conpass').value;
    const dob = document.getElementById('birth').value;
    const country = document.getElementById('country').value;
    const terms = document.getElementById('checkbox').checked;
    const messageBox = document.querySelector('.invalid');

    // Reset label colors and input borders to default
    const labels = document.querySelectorAll("label");
    labels.forEach(label => label.style.color = "#000");
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => input.style.borderColor = "");

    // Regular expressions for validation
    const nameRegex = /^[a-zA-Z.\-\s]+$/;
    const emailRegex = /^[\w.-]+@(gmail|hotmail|yahoo)\.com$/;

    // Password complexity checks
    const passwordLength = password.length >= 8;
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasDigit = /\d/.test(password);
    const hasSpecial = /[^A-Za-z0-9]/.test(password);

    // Calculate age from date of birth
    const dobDate = new Date(dob);
    const age = new Date().getFullYear() - dobDate.getFullYear();
    const isValidAge = age > 18;

    // Begin validation checks
    if (!nameRegex.test(name) || name.length <= 3) {
        highlightError('fullname', "Name must be more than 3 characters and valid.");
    } else if (!genderMale && !genderFemale) {
        highlightError('gender', "Please select a gender.");
    } else if (!emailRegex.test(email)) {
        highlightError('email', "Email must be valid and end with gmail, hotmail, or yahoo.");
    } else if (!passwordLength || !hasLower || !hasUpper || !hasDigit || !hasSpecial) {
        highlightError('pass', "Password must be at least 8 characters and include upper, lower, digit, and special.");
    } else if (password !== confirmPassword) {
        highlightError('conpass', "Passwords do not match.");
    } else if (!dob || !isValidAge) {
        highlightError('birth', "You must be older than 18.");
    } else if (country === "") {
        highlightError('country', "Please select a country.");
    } else if (!terms) {
        highlightError('checkbox', "You must accept the terms.");
    } else {
        // If all validations pass
        messageBox.textContent = "Everything good";
        messageBox.style.color = "green";
        messageBox.style.display = "block";

        // Hide the message after 2 seconds
        setTimeout(() => {
            messageBox.style.display = "none";
        }, 2000);

        return false; // Prevent actual form submission
    }

    return false; // Prevent submission if validation fails
}

// Function to highlight input field with an error
function highlightError(fieldId, message) {
    const messageBox = document.querySelector('.invalid');
    messageBox.textContent = message;
    messageBox.style.color = "red";
    messageBox.style.display = "block";

    const label = document.querySelector(`label[for="${fieldId}"]`);
    if (label) {
        label.style.color = "red";
    }

    const input = document.getElementById(fieldId);
    if (input) {
        input.style.borderColor = "red";
    }
}

// Function to clear all form inputs and reset styles
function clearForm() {
    // Clear values of all form fields
    document.getElementById('fullname').value = "";
    document.getElementById('male').checked = false;
    document.getElementById('female').checked = false;
    document.getElementById('email').value = "";
    document.getElementById('pass').value = "";
    document.getElementById('conpass').value = "";
    document.getElementById('birth').value = "";
    document.getElementById('country').value = "SC";
    document.getElementById('checkbox').checked = false;
    document.getElementById('opinion').value = "";
    document.getElementById('bgc').value = "#000000";

    // Clear message and reset styles
    document.querySelector('.invalid').textContent = "";
    document.getElementById('data') && (document.getElementById('data').textContent = "");

    const labels = document.querySelectorAll("label");
    labels.forEach(label => label.style.color = "#000");
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.style.borderColor = "";
        if (input.type === 'password' || input.type === 'text') {
            input.type = 'password'; // Reset input type to password for security
        }
    });

    // Reset password visibility toggle icons
    document.querySelectorAll('.toggle-password').forEach(eye => {
        eye.textContent = '🙈';
    });
}

// DOM loaded event: add password visibility toggles
document.addEventListener('DOMContentLoaded', function() {
    // Helper function to add eye icon next to password fields
    const addEyeIcon = (fieldId) => {
        const field = document.getElementById(fieldId);
        const eye = document.createElement('span');
        eye.className = 'toggle-password';
        eye.innerHTML = '🙈';
        eye.style.position = 'absolute';
        eye.style.right = '10px';
        eye.style.cursor = 'pointer';
        field.parentNode.style.position = 'relative';
        field.parentNode.insertBefore(eye, field.nextSibling);
    };

    // Add eye icons to password and confirm password fields
    addEyeIcon('pass');
    addEyeIcon('conpass');

    // Add toggle functionality to eye icons
    document.querySelectorAll('.toggle-password').forEach(eye => {
        eye.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.textContent = isPassword ? '👀' : '🙈'; // Change icon on toggle
        });
    });
});
