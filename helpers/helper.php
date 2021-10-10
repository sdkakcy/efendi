<?php

use App\Facades\Request;
use App\Facades\Response;
use App\Facades\Route;
use App\Facades\Session;
use App\Facades\View;
use \Carbon\Carbon;
use \Cocur\Slugify\Slugify;

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('request')) {
    /**
     * request
     *
     * @param [string] $key
     * @param [mixed] $default
     * @return Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return Request::getInstance();
        }

        return Request::getInstance()->get($key, $default);
    }
}

if (!function_exists('abort')) {
    function abort($view, $status, $data = [])
    {
        if (request()->isAjax()) {
            return response($status, $status);
        }

        return response(View::getInstance()->render($view, $data, true), $status);
    }
}

if (!function_exists('view')) {
    function view($name, $data = [], $return = false)
    {
        return View::getInstance()->render($name, $data, $return);
    }
}

if (!function_exists('response')) {
    /**
     * response
     *
     * @param string|array $response
     * @param int $status
     * @return Response|string
     */
    function response($response = null, $status = 200)
    {
        if (is_null($response)) {
            return Response::getInstance();
        }

        return Response::getInstance()->response($response, $status);
    }
}

if (!function_exists('session')) {
    /**
     * session
     *
     * @param string $key
     * @param string|array $default
     * @return Session|string|array
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return Session::getInstance();
        }

        return Session::getInstance()->get($key, $default);
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        if (session('kullanici_id')) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('result')) {
    function result($e)
    {
        return [
            'success' => false,
            'type' => $e->getType(),
            'status' => $e->getCode(),
            'message' => $e->getMessage()
        ];
    }
}

if (!function_exists('route')) {
    function route($name, $params = [])
    {
        if (isset(Route::getInstance()->routes[$name])) {
            $url = Route::getInstance()->routes[$name][1];
            $query = [];
            foreach ($params as $key => $value) {
                if (preg_match('/\{(' . $key . ')\}/u', $url)) {
                    $url = preg_replace('/\{(' . $key . ')\}/u', $value, $url);
                } else {
                    $query[$key] = $value;
                }
            }

            if (!empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        return $url ?? null;
    }
}

if (!function_exists('slugify')) {
    function slugify($string)
    {
        $slugify = new Slugify();
        $slugify->activateRuleSet('turkish');
        return $slugify->slugify($string);
    }
}

if (!function_exists('redirect')) {
    function redirect($path)
    {
        header("Location: {$path}");
        exit();
    }
}


if (!function_exists('asset')) {
    function asset($path)
    {
        if (env('USE_CDN')) {
            return env('CDN_URL') . '/v' . ASSET_VER . $path;
        }

        return $path . '?v=' . ASSET_VER;
    }
}

/**
 * @return Carbon
 */
if (!function_exists('now')) {
    function now()
    {
        return Carbon::now();
    }
}
