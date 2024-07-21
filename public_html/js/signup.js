function validateForm(){
    const fullname = document.getElementById('fullname').value;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    if(fullname === '' || username === '' || password === ''){
        alert('All fields are required');
        return;
    }

    if(!validateEmail(email)){
        alert('Please enter a valid email');
        return;
    }else{
        document.getElementById('signupForm').submit();
    }
}
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

//Function to toggle password field 
function togglePassword() {
    const passwordField = document.getElementById('password');
    const togglePasswordIcon = document.querySelector('.toggle-password i');
    const togglePasswordText = document.querySelector('.toggle-password span');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        togglePasswordIcon.className = 'fas fa-eye-slash';
        togglePasswordText.textContent = 'Hide';
    } else {
        passwordField.type = 'password';
        togglePasswordIcon.className = 'fas fa-eye';
        togglePasswordText.textContent = 'Show';
    }
}
