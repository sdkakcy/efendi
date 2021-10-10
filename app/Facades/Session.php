<?php

namespace App\Facades;

class Session
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

    public function get($key, $default)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function all()
    {
        return $_SESSION;
    }

    public function fill($items = [])
    {
        $_SESSION = array_merge($_SESSION, $items);

        return $this;
    }
}
