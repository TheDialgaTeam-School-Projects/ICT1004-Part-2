<?php

namespace Controller;

use Exception;
use Model\AuthenticationHandler;
use Model\DatabaseContext;
use Model\EmailHandler;

abstract class AbstractController
{
    /**
     * @var DatabaseContext
     */
    protected $databaseContext;

    /**
     * @var AuthenticationHandler
     */
    protected $authenticationHandler;

    /**
     * @var EmailHandler
     */
    protected $emailHandler;

    public function __construct()
    {
        $this->databaseContext = new DatabaseContext();
        $this->authenticationHandler = new AuthenticationHandler();
        $this->emailHandler = new EmailHandler();
    }

    public function doAction(): array
    {
        $result = [];

        try {
            if (isset($_GET['action'])) {
                if ($result = call_user_func([$this, $_GET['action'] . 'Action']))
                    return $result;
                else
                    return [];
            }

            if (isset($_POST['action'])) {
                if ($result = call_user_func([$this, $_POST['action'] . 'Action']))
                    return $result;
                else
                    return [];
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return array_merge($this->indexAction(), $result);
    }

    protected abstract function indexAction(): array;

    /**
     * Generate a new unique csrf token.
     * @param string $key Csrf key to store into the session.
     * @return string
     * @throws Exception
     */
    protected function GenerateCsrfToken(string $key): string
    {
        $_SESSION[$key] = bin2hex(random_bytes(32));
        return $_SESSION[$key];
    }

    /**
     * Verify if the csrf token matches the one store in the server.
     * @param string $key Csrf key from the session.
     * @param string $value Csrf token to check against.
     * @return bool
     */
    protected function VerifyCsrfToken(string $key, string $value): bool
    {
        $result = false;

        if (isset($key) && isset($_SESSION[$key]) && isset($value)) {
            $result = hash_equals($_SESSION[$key], $value);
            unset($_SESSION[$key]);
        }

        return $result;
    }
}