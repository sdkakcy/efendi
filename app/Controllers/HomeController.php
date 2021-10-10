<?php

namespace App\Controllers;

use App\Core\Interfaces\FrontController;

class HomeController extends Controller implements FrontController
{
    public function index()
    {
        return view('home');
    }
}
