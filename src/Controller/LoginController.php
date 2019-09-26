<?php

namespace Controller;

use Exception;

class LoginController extends AbstractController
{
    protected function indexAction(): array
    {
        $csrf = null;
        $error = null;

        $this->authenticationHandler->redirectFromHomePage();

        try {
            $csrf = $this->GenerateCsrfToken('loginCsrf');
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return [
            'csrf' => $csrf,
            'error' => $error,
        ];
    }

    protected function loginAction(): array
    {
        if (!$this->VerifyCsrfToken('loginCsrf', $_POST['loginCsrf'] ?? '')) {
            return array_merge($this->indexAction(), [
                'error' => CSRF_ERROR
            ]);
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $validationResult = [];

        if (!isset($username) || strlen($username) === 0)
            $validationResult['username_error'] = 'Username is empty!';
        elseif (strlen($username) > 50)
            $validationResult['username_error'] = 'Username is too long! Maximum allowed characters: 50.';
        elseif (!preg_match('/^\w+$/', $username))
            $validationResult['username_error'] = 'Username only allows alphanumeric & underscore characters!';

        if (!isset($password) || strlen($password) === 0)
            $validationResult['password_error'] = 'Password is empty!';
        elseif (strlen($password) > 2048)
            $validationResult['password_error'] = 'Password is too long! Maximum allowed characters: 2048.';

        if (count($validationResult) > 0) {
            return array_merge($this->indexAction(), [
                'username_error' => $validationResult['username_error'] ?? null,
                'password_error' => $validationResult['password_error'] ?? null,
            ]);
        }

        $error = null;

        try {
            $user = $this->databaseContext->userDb->getUserByUsername($username);

            if (count($user) == 0) {
                return array_merge($this->indexAction(), [
                    'username_error' => 'User is not registered!'
                ]);
            }

            $userId = $user['user_id'];
            $verified = $user['verified'];

            if (!$verified) {
                $csrf = $this->GenerateCsrfToken('actionHandlerCsrf');

                return array_merge($this->indexAction(), [
                    'error' => "User is not verified! Please check your email for account activation. If you require need to get a new verification mail, click <a href='actionHandler.php?action=resendVerificationMail&user_id=$userId&csrf=$csrf'>here</a>."
                ]);
            }

            $passwordHash = $user['password'];

            if (!password_verify($password, $passwordHash)) {
                return array_merge($this->indexAction(), [
                    'password_error' => 'Password mismatch!'
                ]);
            }

            $name = $user['name'];
            $type = $user['type'];

            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_type'] = $type;

            $this->authenticationHandler->redirectFromHomePage();

            return [];
        } catch (Exception $e) {
            $error = $e->getMessage();

            return array_merge($this->indexAction(), [
                'error' => $error
            ]);
        }
    }
}