<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureStoreIsActive;
use App\Http\Middleware\EnsureEmailIsVerifiedCustom;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'active.store' => EnsureStoreIsActive::class,
            'verified.custom' => EnsureEmailIsVerifiedCustom::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();