<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'front.user' => \App\Http\Middleware\EnsureFrontUser::class,
            'front.plan' => \App\Http\Middleware\EnsureActiveSubscription::class,
            'agent' => \App\Http\Middleware\EnsureAgent::class,
            'admin.module' => \App\Http\Middleware\EnsureAdminModule::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\LogImpersonatedActions::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'razorpay/webhook',
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('agent:open-expired-profiles')->everyFifteenMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
