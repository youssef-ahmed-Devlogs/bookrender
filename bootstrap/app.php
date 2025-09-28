<?php

use App\Http\Middleware\AdminAuthorizeRedirect;
use App\Http\Middleware\OTP;
use App\Http\Middleware\ReachedMaximumBooks;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin-authorize' => AdminAuthorizeRedirect::class,
            'reached-maximum-books' => ReachedMaximumBooks::class,
            'otp' => OTP::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'paddle/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
