<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\EnsureGetMethodForPositions;
use App\Http\Middleware\EnsureGetMethodForToken;
use App\Http\Middleware\ValidateRequestHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'token.auth' => \App\Http\Middleware\TokenAuth::class,
        ]);

        $middleware->append(EnsureGetMethodForPositions::class);
        $middleware->append(EnsureGetMethodForToken::class);
        $middleware->append(ValidateRequestHeaders::class);
        

    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
