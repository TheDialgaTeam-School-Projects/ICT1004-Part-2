<?php

namespace Model;

use Model\Database\UserDb;

class DatabaseContext
{
    /**
     * @var UserDb
     */
    public $userDb;

    public function __construct()
    {
        $this->userDb = new UserDb();
    }
}