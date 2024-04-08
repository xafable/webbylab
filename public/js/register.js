
const registerRefs = {
    username: document.querySelector('.register-form #username'),
    password: document.querySelector('.register-form #password'),
    repeatPassword: document.querySelector('.register-form #repeatPassword'),
    registerButton: document.querySelector('#registerButton'),
    registerForm: document.querySelector('.register-form'),
}

const valids = {
    username: false,
    password: false,
    repeatPassword: false
}

registerRefs.username.addEventListener('input', (event) => {
    const usernameError = event.currentTarget.nextElementSibling;



    if(event.currentTarget.value.trim().length === 0) {
        usernameError.style.display = 'block';
        usernameError.innerHTML = 'Please enter a username';
        disableRegisterButton();
        return;
    }

    if(event.currentTarget.value.trim().length < 3) {
        usernameError.style.display = 'block';
        usernameError.innerHTML = 'Username must be at least 3 characters';
        disableRegisterButton();
        return;
    }

    if(event.currentTarget.value.trim().length > 20) {
        usernameError.style.display = 'block';
        usernameError.innerHTML = 'Username must be less than 20 characters';
        disableRegisterButton();
        return;
    }

    
    usernameError.style.display = 'none';
    valids.username = true;
    checkValids();
    
})

registerRefs.password.addEventListener('input', (event) => {
    const passwordError = event.currentTarget.nextElementSibling;
 

    if(event.currentTarget.value.trim().length === 0) {
        passwordError.style.display = 'block';
        passwordError.innerHTML = 'Please enter a password';
        disableRegisterButton();
        return;
    }

    if(event.currentTarget.value.trim().length < 6) {
        passwordError.style.display = 'block';
        passwordError.innerHTML = 'Password must be at least 6 characters';
        disableRegisterButton();
        return;
    }

    if(event.currentTarget.value.trim().length > 20) {
        passwordError.style.display = 'block';
        passwordError.innerHTML = 'Password must be less than 20 characters';
        disableRegisterButton();
        return;
    }


    passwordError.style.display = 'none';
    valids.password = true;
    checkValids();
   

})


registerRefs.repeatPassword.addEventListener('input', (event) => {
    const repeatPasswordError = event.currentTarget.nextElementSibling;



    if(event.currentTarget.value.trim() !== registerRefs.password.value) {
        repeatPasswordError.style.display = 'block';
        repeatPasswordError.innerHTML = 'Passwords do not match';
        disableRegisterButton();
        return; 
    }


    repeatPasswordError.style.display = 'none';
    valids.repeatPassword = true;
    checkValids();
   

})


document.querySelector('#registerButton').addEventListener('click', (event) => {

    event.preventDefault();

    registerRefs.registerForm.submit();


})

function disableRegisterButton() {
    registerRefs.registerButton.disabled = true;
    registerRefs.registerButton.style.opacity = '0.5';
}

function enableRegisterButton() {
    registerRefs.registerButton.disabled = false;
    registerRefs.registerButton.style.opacity = '1';
}

function checkValids() {

    if(valids.username && valids.password && valids.repeatPassword) {
        enableRegisterButton();
    }
    else {
        disableRegisterButton();
    }
}
