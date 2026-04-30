<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            if (in_array('guest', $roles)) {
                \Illuminate\Support\Facades\DB::table('activity_logs')->insert([
                    'username' => 'guest',
                    'role' => 'guest',
                    'activity' => $request->path(),
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $next($request);
            }
            abort(403, 'Unauthorized access.');
        }

        $user = auth()->user();

        $userRole = $user->roles->first()->name ?? ($user->is_admin ? 'admin' : 'member');

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized role access.');
        }

        \Illuminate\Support\Facades\DB::table('activity_logs')->insert([
            'username' => $user->username ?? $user->name ?? $user->email,
            'role' => $userRole,
            'activity' => $request->path(),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $next($request);
    }
}
