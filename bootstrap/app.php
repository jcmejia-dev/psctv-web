<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\EventServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        if (getenv('VERCEL')) {
            $middleware->trustProxies(at: '*');
        }
        // ✅ Custom middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check_staff_module' => \App\Http\Middleware\CheckStaffModuleAccess::class,
            'staff_permission' => \App\Http\Middleware\CheckStaffPermission::class,
        ]);

        // ✅ Sanctum Middleware for API authentication
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $exception) {
            if (getenv('VERCEL')) {
                error_log('PSCTV application: '.get_class($exception).': '.$exception->getMessage());
            }
        });
    })
    ->create();
