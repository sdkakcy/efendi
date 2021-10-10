<?php

namespace App\Facades;

class Route
{
    protected static $instance = null;

    public $routes = [];

    public $middlewares = [];

    public $prefix = null;

    public $as = null;

    /**
     * get View instance
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function route($method, $url, $path)
    {
        $this->routes[] = [$method, $url, $path];

        $this->routes[array_key_last($this->routes)]['middlewares'] = $this->middlewares;
    }

    public function group($options, $callback)
    {
        $this->prefix .= $options['prefix'] ?? null;
        $this->as .= $options['as'] ?? null;
        $this->middlewares = array_merge($this->middlewares, $options['middleware'] ?? []);

        call_user_func($callback, $this);

        if (isset($options['prefix'])) {
            $this->prefix = substr($this->prefix, 0, strrpos($this->prefix, $options['prefix']));
        }

        if (isset($options['as'])) {
            $this->as = substr($this->as, 0, strrpos($this->as, $options['as']));
        }

        if (isset($options['middleware'])) {
            foreach ($options['middleware'] as $m) {
                if (($key = array_search($m, $this->middlewares)) !== false) {
                    unset($this->middlewares[$key]);
                }
            }
        }
    }

    public function name($name)
    {
        if ($this->as) {
            $name = $this->as . $name;
        }

        $last = array_pop($this->routes);

        $this->routes[$name] = $last;
    }

    public function get($url, $path)
    {
        if ($this->prefix) {
            $url = $this->prefix . $url;
        }

        $this->route('GET', $url, $path);

        return $this;
    }

    public function post($url, $path)
    {
        if ($this->prefix) {
            $url = $this->prefix . $url;
        }

        $this->route('POST', $url, $path);

        return $this;
    }
}
