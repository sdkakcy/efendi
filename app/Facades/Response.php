<?php

namespace App\Facades;

class Response
{
    public static $instance = null;

    /**
     * get View instance
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function response($response = null, $status = 200)
    {
        http_response_code($status);

        echo $response;

        exit($status);
    }

    public function json($response = [], $status = 200)
    {
        http_response_code($status);

        echo json_encode($response);

        exit($status);
    }
}
