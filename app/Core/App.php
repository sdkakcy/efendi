<?php

namespace App\Core;

use App\Facades\Route;

class App
{
    protected static $routes;

    protected static $config;

    protected $activePath;

    protected $activeMethod;

    protected $params;

    protected $notFound;

    public function __construct($activePath, $activeMethod, $config)
    {
        self::$config = $config;

        self::$routes = Route::getInstance()->routes;

        $this->activePath = $activePath;
        $this->activeMethod = $activeMethod;

        $this->notFound = function () {
            return abort('panel.errors.404', 404);
        };
    }

    public function run()
    {
        foreach (self::$routes as $route) {
            list($method, $url, $path) = $route;

            $methodCheck = $this->activeMethod == $method;

            // En son karakter / ise kaldır
            if (strlen($this->activePath) > 1 && substr($this->activePath, -1) == '/') {
                $this->activePath = substr($this->activePath, 0, -1);
            }

            // GET isteklerini ayırma
            $this->activePath = explode('?', $this->activePath)[0];

            $url = preg_replace('/\{(.*?)\}/u', '([^\/]+)', $url);

            $urlCheck = preg_match("~^{$url}/*$~", $this->activePath, $this->params);

            if ($methodCheck && $urlCheck) {
                if (is_callable($path)) {
                    return call_user_func($path);
                }

                $path = explode('@', $path);

                list($className, $action) = $path;

                foreach ($route['middlewares'] as $middleware) {
                    self::$config['aliases'][$middleware]::getInstance()->handle($className);
                }

                $controller = 'App\Controllers\\' . $className;

                $class = new $controller;

                array_shift($this->params);

                if (!session()->has('token')) {
                    session()->set('token', bin2hex(random_bytes(32)));
                }

                return call_user_func_array([$class, $action], array_values($this->params));
            }
        }

        return call_user_func($this->notFound);
    }
}
