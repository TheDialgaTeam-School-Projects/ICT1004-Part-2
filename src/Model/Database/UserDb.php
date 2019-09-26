<?php

namespace Model\Database;

use Exception;
use MysqliExtensions;

class UserDb
{
    /**
     * Get user information by user id.
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getUserByUserId(int $userId): array
    {
        $result = [];

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'select * from user where user_id = ?', $userId)) {
                $result = $query->fetch_assoc() ?? [];
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();

            return $result;
        }
    }

    /**
     * Get user information by username.
     * @param string $username Username of the user.
     * @return array An array containing the user information.
     * @throws Exception
     */
    public function getUserByUsername(string $username): array
    {
        $result = [];

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'select * from user where username = ?', $username)) {
                $result = $query->fetch_assoc() ?? [];
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();

            return $result;
        }
    }

    /**
     * Check if the email exists in the database.
     * @param string $email Email of the user.
     * @return bool
     * @throws Exception
     */
    public function checkIfEmailExists(string $email): bool
    {
        $result = 0;

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'select count(*) from user where email = ?', $email)) {
                $result = $query->fetch_assoc()['count(*)'];
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();
        }

        return $result > 0;
    }

    /**
     * Check if the username exists in the database.
     * @param string $username Username of the user.
     * @return bool
     * @throws Exception
     */
    public function checkIfUsernameExists(string $username): bool
    {
        $result = 0;

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'select count(*) from user where username = ?', $username)) {
                $result = $query->fetch_assoc()['count(*)'];
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();
        }

        return $result > 0;
    }

    /**
     * Insert new user into the database.
     * @param string $name
     * @param string $email
     * @param string $username
     * @param string $passwordHash
     * @param string $dob
     * @param string $gender
     * @param string $verificationHash
     * @throws Exception
     */
    public function insertNewUser(string $name, string $email, string $username, string $passwordHash, string $dob, string $gender, string $verificationHash): void
    {
        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'insert into user(name, email, username, password, dob, gender, verification_hash) VALUES (?, ?, ?, ?, ?, ?, ?)', $name, $email, $username, $passwordHash, $dob, $gender, $verificationHash)) {
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();
        }
    }

    /**
     * Update verification hash.
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function updateVerificationHash(int $userId): string
    {
        $verificationHash = md5(time());

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'update user set verification_hash = ? where user_id = ?', $verificationHash, $userId)) {
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();
        }

        return $verificationHash;
    }

    /**
     * Update the user to be verified.
     * @param int $userId
     * @throws Exception
     */
    public function updateUserVerified(int $userId): void
    {
        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'update user set verified = 1 where user_id = ?', $userId)) {
                $query->close();
            }
        } finally {
            if (isset($db))
                $db->close();
        }
    }
}