<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function(): void {
            Route::middleware("web")->group(function (): void {
            Route::group([], __DIR__ . "/../routes/web.php");
            Route::group([], __DIR__ . "/../routes/auth.php");
        });
    },
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: "*");
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();
