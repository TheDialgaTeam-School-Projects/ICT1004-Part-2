<?php

namespace Controller;

use Exception;

class RegisterController extends AbstractController
{
    protected function indexAction(): array
    {
        $csrf = null;
        $error = null;

        $this->authenticationHandler->redirectFromHomePage();

        try {
            $csrf = $this->GenerateCsrfToken('registerCsrf');
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return [
            'csrf' => $csrf,
            'error' => $error,
        ];
    }

    protected function registerAction(): array
    {
        if (!$this->VerifyCsrfToken('registerCsrf', $_POST['registerCsrf'] ?? '')) {
            return array_merge($this->indexAction(), [
                'error' => CSRF_ERROR
            ]);
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];
        $dob = trim($_POST['dob'] ?? '');
        $gender = trim($_POST['gender'] ?? '');

        $validationResult = [];

        if (!isset($name) || strlen($name) === 0)
            $validationResult['name_error'] = 'Name is empty!';
        elseif (strlen($name) > 255)
            $validationResult['name_error'] = 'Name is too long! Maximum allowed characters: 255.';

        if (!isset($email) || strlen($email) === 0)
            $validationResult['email_error'] = 'Email is empty!';
        elseif (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', trim($email)))
            $validationResult['email_error'] = 'Invalid email address!';

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
        elseif ($password != $passwordConfirm)
            $validationResult['password_error'] = 'Password do not match!';

        if (!isset($dob) || !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $dob))
            $validationResult['dob_error'] = 'Select a date of birth!';
        elseif (strtotime($dob) < strtotime(sprintf('%s-%s-%s', date('Y') - 100, date('m'), date('d'))) || strtotime($dob) > time())
            $validationResult['dob_error'] = 'Date of birth is invalid!';
        elseif (date("Y-m-d", strtotime($dob)) != $dob)
            $validationResult['dob_error'] = 'Date of birth is invalid!';

        if (!isset($gender) || ($gender != 'male' && $gender != 'female'))
            $validationResult['error'] = 'Gender is invalid!';

        if (count($validationResult) > 0) {
            return array_merge($this->indexAction(), [
                'name_error' => $validationResult['name_error'] ?? null,
                'email_error' => $validationResult['email_error'] ?? null,
                'username_error' => $validationResult['username_error'] ?? null,
                'password_error' => $validationResult['password_error'] ?? null,
                'dob_error' => $validationResult['dob_error'] ?? null,
                'error' => $validationResult['error'] ?? null,
            ]);
        }

        $error = null;

        try {
            if ($this->databaseContext->userDb->checkIfEmailExists($email))
                $validationResult['email_error'] = 'This email have been registered.';

            if ($this->databaseContext->userDb->checkIfUsernameExists($username))
                $validationResult['username_error'] = 'This username have been taken.';

            if (count($validationResult) > 0) {
                return array_merge($this->indexAction(), [
                    'email_error' => $validationResult['email_error'],
                    'username_error' => $validationResult['username_error'],
                ]);
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $verificationHash = md5(time());

            $this->databaseContext->userDb->insertNewUser($name, $email, $username, $passwordHash, $dob, $gender, $verificationHash);

            $user = $this->databaseContext->userDb->getUserByUsername($username);

            if (count($user) == 0) {
                return array_merge($this->indexAction(), [
                    'error' => 'Registration failed. There might be some error while trying to insert into database.'
                ]);
            }

            $userId = $user['user_id'];

            $this->emailHandler->sendVerificationMail($userId, $name, $email, $verificationHash);

            $csrf = $this->GenerateCsrfToken('actionHandlerCsrf');
            header("Location: actionHandler.php?action=registerSuccess&csrf=$csrf");

            return [];
        } catch (Exception $e) {
            $error = $e->getMessage();

            return array_merge($this->indexAction(), [
                'error' => $error,
            ]);
        }
    }
}