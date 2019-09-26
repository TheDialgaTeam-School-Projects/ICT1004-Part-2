document.forms['loginForm']['submit'].addEventListener('click', function (e) {
    var usernameElement = document.forms['loginForm']['username'];
    var passwordElement = document.forms['loginForm']['password'];
    var formSubmit = true;

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
    } else if (passwordElement.value.length > 2048) {
        formSubmit = false;
        updateFormElementStatus(passwordElement, false, 'Password is too long! Maximum allowed characters: 2048.');
    } else {
        updateFormElementStatus(passwordElement, true, 'Looks good!');
    }

    if (!formSubmit) {
        e.preventDefault();
        e.stopPropagation();
    }
});