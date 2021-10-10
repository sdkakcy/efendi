<?php

namespace App\Facades;

class Request
{

    public static $instance = null;

    protected $parameters = [];

    protected $segments = [];

    protected $headers = [];

    public function __construct()
    {
        if ($this->isGet()) {
            $this->parameters = array_merge($this->parameters, $_GET);
        } else if ($this->isPost()) {
            $this->parameters = array_merge($this->parameters, $_POST);

            if (isset($_FILES)) {
                $this->parameters = array_merge($this->parameters, $_FILES);
            }
        }

        $this->headers = array_change_key_case(getallheaders(), CASE_LOWER);

        $this->segments = explode('/', parse_url($this->requestUri(), PHP_URL_PATH));
    }

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

    public function token()
    {
        if (session()->has('token')) {
            $token = $this->headers['token'] ?? $this->parameters['token'] ?? '';

            if (hash_equals(session('token'), $token)) {
                return true;
            }
        }

        return false;
    }

    public function set($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function isPost()
    {
        return (isset($_POST) && $_SERVER["REQUEST_METHOD"] == "POST");
    }

    public function isGet()
    {
        return (isset($_GET) && $_SERVER["REQUEST_METHOD"] == "GET");
    }

    public function isAjax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public function get($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($this->parameters[$key]);
    }

    public function referer()
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    public function requestUri()
    {
        return $_SERVER['REQUEST_URI'] ?? null;
    }

    public function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? null;
    }

    public function getCookieKeys()
    {
        return array_keys($_COOKIE) ?? [];
    }

    public function getCookie($key, $default = null)
    {
        return $_COOKIE[$key] ?? $default;
    }

    public function getUri()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->requestUri();
    }

    public function getHost()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    }

    public function segment($segment)
    {
        return $this->segments[$segment] ?? null;
    }

    public function headers($header = null)
    {
        return $header ? ($this->headers[$header] ?? null) : $this->headers;
    }

    public function all()
    {
        return $this->parameters;
    }
}
