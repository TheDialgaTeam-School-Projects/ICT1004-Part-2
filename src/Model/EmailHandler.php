<?php

namespace Model;


class EmailHandler
{
    public function sendVerificationMail(int $userId, string $name, string $email, string $verificationHash): void
    {
        $emailMessage = sprintf("Hi %s,\n\nIn order to complete your registration at %s site, you need to confirm your account by following the link below.\n%s\n\nYour regards,\n%s Administrator",
            $name, SITE_NAME, "http://ict1004.ddns.net/AY18/Group14/project2/actionHandler.php?action=verifyAccount&user_id=$userId&verification_hash=$verificationHash", SITE_NAME);

        mail($email, 'Site Registration from ' . SITE_NAME, $emailMessage, sprintf('From: %s Administrator <%s>', SITE_NAME, SMTP_EMAIL));
    }
}