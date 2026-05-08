<?php

use App\Http\Middleware\authenticate;
use App\Http\Middleware\logedInUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // rename middleware
        $middleware->alias([
            'logedIn'=> logedInUser::class,
            'authenticate'=> authenticate::class,
        ]);    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
