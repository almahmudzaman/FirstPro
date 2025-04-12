function validateForm() {
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

    // Regex and age validation
    const nameRegex = /^[a-zA-Z.\-\s]+$/;
    const emailRegex = /^[\w.-]+@(gmail|hotmail|yahoo)\.com$/;
    const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).+$/;

    const dobDate = new Date(dob);
    const age = new Date().getFullYear() - dobDate.getFullYear();
    const isValidAge = age > 18;

    if (
        nameRegex.test(name) &&
        (genderMale || genderFemale) &&
        emailRegex.test(email) &&
        passwordRegex.test(password) &&
        password === confirmPassword &&
        dob &&
        isValidAge &&
        country !== "" &&
        terms
    ) {
        messageBox.textContent = "Everything good";
        messageBox.style.display = "block";

        setTimeout(() => {
            messageBox.style.display = "none";
        }, 2000);

        return false; // Prevent form submission for demo
    } else {
        messageBox.textContent = "Something going wrong";
        messageBox.style.display = "block";
        return false; // Prevent form submission
    }
}


function clearForm() {
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
    document.getElementById('bgc').value = "#000000"; // optional reset color
    document.querySelector('.invalid').textContent = "";
    document.getElementById('data').textContent = "";
}
