<?php

namespace Controller;

use Exception;

class ActionHandlerController extends AbstractController
{
    protected function indexAction(): array
    {
        return ['header' => 'Invalid Request', 'content' => 'You have made an invalid request to this page.'];
    }

    protected function registerSuccessAction(): array
    {
        if (!$this->VerifyCsrfToken('actionHandlerCsrf', $_GET['csrf'] ?? '')) {
            return array_merge($this->indexAction(), [
                'content' => CSRF_ERROR
            ]);
        }

        return ['header' => 'Registration Complete!', 'content' => 'You should receive an email to activate your account soon.'];
    }

    protected function verifyAccountAction(): array
    {
        try {
            $user = $this->databaseContext->userDb->getUserByUserId($_GET['user_id']);

            if (count($user) == 0) {
                return $this->indexAction();
            }

            $verified = $user['verified'];

            if ($verified)
                return $this->indexAction();

            $verificationHash = $user['verification_hash'];

            if ($verificationHash != $_GET['verification_hash'])
                return ['header' => 'Account not verified!', 'content' => 'Invalid verification hash. Maybe the verification hash is expired.'];

            $this->databaseContext->userDb->updateUserVerified($_GET['user_id']);

            return ['header' => 'Account Verified!', 'content' => 'You can now login to make an appointment.'];
        } catch (Exception $e) {
            return ['header' => 'Unexpected error occurred!', 'content' => $e->getMessage()];
        }
    }

    protected function resendVerificationMailAction(): array
    {
        if (!$this->VerifyCsrfToken('actionHandlerCsrf', $_GET['csrf'] ?? '')) {
            return array_merge($this->indexAction(), [
                'content' => CSRF_ERROR
            ]);
        }

        try {
            $user = $this->databaseContext->userDb->getUserByUserId($_GET['user_id']);

            if (count($user) == 0) {
                return $this->indexAction();
            }

            $name = $user['name'];
            $email = $user['email'];
            $verified = $user['verified'];

            if ($verified) {
                return $this->indexAction();
            }

            $verificationHash = $this->databaseContext->userDb->updateVerificationHash($_GET['user_id']);

            $this->emailHandler->sendVerificationMail($_GET['user_id'], $name, $email, $verificationHash);

            return ['header' => 'Activation mail resent!', 'content' => 'You should receive an email to activate your account soon. Do note that old verification link is now invalidated!'];
        } catch (Exception $e) {
            return ['header' => 'Unexpected error occurred!', 'content' => $e->getMessage()];
        }
    }
}