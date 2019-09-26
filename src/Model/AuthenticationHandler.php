<?php

namespace Model;

class AuthenticationHandler
{
    public function redirectFromHomePage(): void
    {
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'patient')
            header(sprintf('Location: %s', PATIENT_HOME_PAGE));
        else if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor')
            header(sprintf('Location: %s', DOCTOR_HOME_PAGE));
    }

    public function redirectFromPatientPage(): void
    {
        if (!isset($_SESSION['user_type']))
            header(sprintf('Location: %s', HOME_PAGE));
        else if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor')
            header(sprintf('Location: %s', DOCTOR_HOME_PAGE));
    }

    public function redirectFromDoctorPage(): void
    {
        if (!isset($_SESSION['user_type']))
            header(sprintf('Location: %s', HOME_PAGE));
        else if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'patient')
            header(sprintf('Location: %s', PATIENT_HOME_PAGE));
    }
}