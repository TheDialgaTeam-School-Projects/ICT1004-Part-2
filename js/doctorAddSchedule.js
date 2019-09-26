document.forms['AppointmentForm']['submit'].addEventListener('click', function (e) {
    var venueElement = document.forms['AppointmentForm']['venue'];
    var timeElement = document.forms['AppointmentForm']['time'];
    var dateElement = document.forms['AppointmentForm']['date'];
    var formSubmit = true;

    if (!/^\d+-\d+-\d+$/.test(dateElement.value)) {
        formSubmit = false;
        updateFormElementStatus(dateElement, false, 'Select a proper Date!');
    } else {
        updateFormElementStatus(dateElement, true, 'Looks good! (*^▽^*)');
    }

    if ((timeElement.value) < 1 || (timeElement.value) > 8) {
        formSubmit = false;
        updateFormElementStatus(timeElement, false, 'Select a proper time!');
    } else {
        updateFormElementStatus(timeElement, true, 'Looks good! (*^▽^*)');
    }
    if ((venueElement.value) < 1 || (venueElement.value) > 22) {
        formSubmit = false;
        updateFormElementStatus(venueElement, false, 'Select a proper venue!');
    } else {
        updateFormElementStatus(venueElement, true, 'Looks good! (*^▽^*)');
    }

    if (!formSubmit) {
        e.preventDefault();
        e.stopPropagation();
    }

});