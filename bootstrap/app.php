<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckApprovedUser;
use App\Http\Middleware\IsSuperAdmin; // <--- Import IsSuperAdmin

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'approved' => CheckApprovedUser::class,
            'superadmin' => IsSuperAdmin::class, // <--- Register alias here
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();