<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Cache::rememberForever(
            'global.settings',
            fn () => Setting::query()->first()
        );

        if ($setting && $setting->is_maintenance) {
            // Allow admin area, login, and logout
            if ($request->is('admin') || $request->is('admin/*') || $request->is('login') || $request->is('logout') || $request->is('masuk')) {
                return $next($request);
            }

            // Allow authenticated admin
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                return $next($request);
            }

            // Show maintenance view or fallback
            return response()->view('errors.maintenance', [
                'setting' => $setting,
                'message' => $setting->system_settings['maintenance_message'] ?? 'Kami sedang melakukan pemeliharaan rutin. Silakan kembali lagi nanti.'
            ], 503);
        }

        return $next($request);
    }
}
