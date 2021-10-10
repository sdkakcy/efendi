<?php

namespace App\Services;

use MysqliDb;

trait QueryBuilder
{
    public $db = null;

    /**
     * MysqliDb QueryBuilder
     *
     * @return MysqliDb
     */
    public function db()
    {
        if (!$this->db) {
            $this->db = new MysqliDb(HOST, USER, PASS, DBNAME, null, 'utf8mb4');
        }

        return $this->db;
    }
}
