<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\ApiTokenAuth;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'api.token' => ApiTokenAuth::class,
            'log.activity' => \App\Http\Middleware\LogActivity::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request): string {
            return $request->is('admin/*')
                ? route('login')
                : route('user.login');
        });
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        $schedule->command('sitemap:generate')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
