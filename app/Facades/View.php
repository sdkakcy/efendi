<?php

namespace App\Facades;

use App\Services\QueryBuilder;
use Jenssegers\Blade\Blade;

class View
{
    use QueryBuilder;

    public static $instance = null;

    protected $blade;

    public function __construct()
    {
        $this->blade = new Blade(DIR . 'resources/views', DIR . 'storage/cache/views');

        $this->boot();
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

    public function boot()
    {
    }

    public function render($name, $data = [], $return = false)
    {
        if ($return) {
            return $this->blade->render($name, $data);
        }

        echo $this->blade->render($name, $data);

        return;
    }
}
