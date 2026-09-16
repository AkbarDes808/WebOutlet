<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /**
         * TRUST CLOUDFLARE / REVERSE PROXY
         * Wajib untuk HTTPS + CSRF + SESSION
         */
        $middleware->trustProxies(at: '*');

        /**
         * Redirect user setelah login
         */
        $middleware->redirectTo(users: '/dashboard');
        
         $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'shift.closed' => \App\Http\Middleware\CheckShiftClosed::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
