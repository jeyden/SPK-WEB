<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole; // Jangan lupa import middleware Anda

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware di sini
        $middleware->alias([
            'role' => CheckRole::class,
            'student.onboarding' => \App\Http\Middleware\CheckStudentOnboarding::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'registration.open' => \App\Http\Middleware\EnsureRegistrationPeriodOpen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();