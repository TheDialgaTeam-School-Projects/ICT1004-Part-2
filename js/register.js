document.forms['registerForm']['submit'].addEventListener('click', function (e) {
    var nameElement = document.forms['registerForm']['name'];
    var emailElement = document.forms['registerForm']['email'];
    var usernameElement = document.forms['registerForm']['username'];
    var passwordElement = document.forms['registerForm']['password'];
    var passwordConfirmElement = document.forms['registerForm']['passwordConfirm'];
    var dobElement = document.forms['registerForm']['dob'];
    var formSubmit = true;

    if (nameElement.value.trim().length === 0) {
        formSubmit = false;
        updateFormElementStatus(nameElement, false, 'Name is empty!');
    } else if (nameElement.value.trim().length > 255) {
        formSubmit = false;
        updateFormElementStatus(nameElement, false, 'Name is too long! Maximum allowed characters: 255.');
    } else {
        updateFormElementStatus(nameElement, true, 'Looks good!');
    }

    if (emailElement.value.trim().length === 0) {
        formSubmit = false;
        updateFormElementStatus(emailElement, false, 'Email is empty!');
    } else if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(emailElement.value.trim())) {
        formSubmit = false;
        updateFormElementStatus(emailElement, false, 'Invalid email address!');
    } else {
        updateFormElementStatus(emailElement, true, 'Looks good!');
    }

    if (usernameElement.value.trim().length === 0) {
        formSubmit = false;
        updateFormElementStatus(usernameElement, false, 'Username is empty!');
    } else if (usernameElement.value.trim().length > 50) {
        formSubmit = false;
        updateFormElementStatus(usernameElement, false, 'Username is too long! Maximum allowed characters: 50.');
    } else if (!/^\w+$/.test(usernameElement.value.trim())) {
        formSubmit = false;
        updateFormElementStatus(usernameElement, false, 'Username only allows alphanumeric & underscore characters!');
    } else {
        updateFormElementStatus(usernameElement, true, 'Looks good!');
    }

    if (passwordElement.value.length === 0) {
        formSubmit = false;
        updateFormElementStatus(passwordElement, false, 'Password is empty!');
        updateFormElementStatus(passwordConfirmElement, false, '');
    } else if (passwordElement.value.length > 2048) {
        formSubmit = false;
        updateFormElementStatus(passwordElement, false, 'Password is too long! Maximum allowed characters: 2048.');
        updateFormElementStatus(passwordConfirmElement, false, '');
    } else if (passwordElement.value !== passwordConfirmElement.value) {
        formSubmit = false;
        updateFormElementStatus(passwordElement, true, '');
        updateFormElementStatus(passwordConfirmElement, false, 'Password do not match!');
    } else {
        updateFormElementStatus(passwordElement, true);
        updateFormElementStatus(passwordConfirmElement, true, '');
    }

    if (!/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/.test(dobElement.value)) {
        formSubmit = false;
        updateFormElementStatus(dobElement, false, 'Date of birth is invalid!');
    } else {
        updateFormElementStatus(dobElement, true, 'Looks good!');
    }

    if (!formSubmit) {
        e.preventDefault();
        e.stopPropagation();
    }
});