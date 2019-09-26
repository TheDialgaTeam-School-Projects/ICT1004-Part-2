<?php

class CsrfExtensions
{
    public static function GenerateCsrfToken(string $key): string
    {
        $csrf = password_hash(time(), PASSWORD_BCRYPT);
        $_SESSION[$key] = $csrf;

        return $csrf;
    }

    public static function ValidateCsrfToken(string $key, string $value): array
    {
        if (!isset($_SESSION[$key]) || $_SESSION[$key] != $value)
            return ['alert' => 'Session is invalid. Please ensure that you click on the link on this website.'];

        unset($_SESSION[$key]);

        return [];
    }
}