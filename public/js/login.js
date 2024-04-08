
console.log('login.js');

const loginRefs = {

    username: document.querySelector('.login-form #username'),
    password: document.querySelector('.login-form #password'),
    loginButton: document.querySelector('#loginButton'),
    loginForm: document.querySelector('.login-form'),
}

const valids = {
    username: false,
    password: false
}


loginRefs.username.addEventListener('input', (event) => {
    const usernameError = event.currentTarget.nextElementSibling;

    if(event.currentTarget.value.trim().length === 0) {
        usernameError.style.display = 'block';
        usernameError.innerHTML = 'Please enter a username';
        disableLoginButton();
        return;
    }

    if(event.currentTarget.value.trim().length < 3) {
        usernameError.style.display = 'block';
        usernameError.innerHTML = 'Username must be at least 3 characters';
        disableLoginButton();
        return;
    }

    usernameError.style.display = 'none';
    valids.username = true;
    checkValids();
})


loginRefs.password.addEventListener('input', (event) => {
    const passwordError = event.currentTarget.nextElementSibling;

    if(event.currentTarget.value.trim().length === 0) {
        passwordError.style.display = 'block';
        passwordError.innerHTML = 'Please enter a password';
        disableLoginButton();
        return;
    }

    if(event.currentTarget.value.trim().length < 6) {
        passwordError.style.display = 'block';
        passwordError.innerHTML = 'Password must be at least 6 characters';
        disableLoginButton();
        return;
    }

    passwordError.style.display = 'none';
    valids.password = true;
    checkValids();

})


loginRefs.loginButton.addEventListener('click', (event) => {
    event.preventDefault(); 

    loginRefs.loginForm.submit();
})


function checkValids() {
    if(valids.username && valids.password) {
        enableLoginButton();
    }
    else {
        disableLoginButton();
    }
}

function disableLoginButton() {
    loginRefs.loginButton.disabled = true;
    loginRefs.loginButton.style.opacity = '0.5';
}

function enableLoginButton() {
    loginRefs.loginButton.disabled = false;
    loginRefs.loginButton.style.opacity = '1';
}