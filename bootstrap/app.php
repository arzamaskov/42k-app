<?php

use App\Shared\UI\Http\Middleware\AdminMiddleware;
use App\Shared\UI\Http\Middleware\AuthenticateMiddleware;
use App\Shared\UI\Http\Middleware\RoleMiddleware;
use App\Shared\UI\Http\Middleware\WebRoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.api' => AuthenticateMiddleware::class,
            'role' => RoleMiddleware::class,
            'role.web' => WebRoleMiddleware::class,
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
