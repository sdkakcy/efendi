<?php

namespace App\Models;

use MysqliDb;

class Model
{
    public $db;

    public function __construct()
    {
        $this->db = new MysqliDb(HOST, USER, PASS, DBNAME, null, 'utf8mb4');
    }
}
