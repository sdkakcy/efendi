<?php

use App\Core\App;

session_start();

require_once 'config/app.php';

$path = request()->requestUri();
$method = request()->requestMethod();

$app = new App($path, $method, $config);
$app->run();
