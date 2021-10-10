<?php

namespace App\Middlewares;

class ExampleMiddleware
{
    use Middleware;
    
    public function handle($request)
    {
        if (!request()->token()) {
            return abort('panel.errors.403', 403);
        }
    }
}
