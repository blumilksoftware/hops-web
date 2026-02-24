<?php

declare(strict_types=1);

use HopsWeb\Http\Middleware\IsAdmin;
use HopsWeb\Http\Middleware\IsTeamMember;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function (): void {
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
        $middleware->alias([
            "is_admin" => IsAdmin::class,
            "is_team_member" => IsTeamMember::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();
