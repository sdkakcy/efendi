<?php

use App\Facades\Route;

$route = Route::getInstance();

$route->get('/', 'HomeController@index')->name('home');