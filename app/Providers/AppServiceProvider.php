<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole(['developer', 'super admin']) ? true : null;
        });

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $setting = \Illuminate\Support\Facades\Cache::rememberForever(
                'global.settings',
                fn () => \App\Models\Setting::query()->first() ?? new \App\Models\Setting()
            );
            $view->with('setting', $setting);
        });

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogSuccessfulLogin::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            \App\Listeners\LogSuccessfulLogout::class
        );

        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            $email = (string) $request->input('email');
            $user = \App\Models\User::where('email', $email)->first();

            $userRole = $user ? ($user->roles->first()->name ?? ($user->is_admin ? 'admin' : 'member')) : 'guest';

            if ($user && in_array($userRole, ['developer', 'super admin'])) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(3)->by($email . $request->ip());
            }

            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($email . $request->ip());
        });
    }
}
