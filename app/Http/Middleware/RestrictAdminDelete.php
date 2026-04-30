<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminDelete
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Cek jika user login dan memiliki role 'admin'
        if ($user && $user->hasRole('admin') && !$user->hasRole(['developer', 'super admin'])) {
            $routeName = $request->route() ? $request->route()->getName() : '';
            
            // Blokir jika method adalah DELETE atau nama route mengandung kata 'destroy' atau 'delete'
            if ($request->isMethod('DELETE') || str_contains($routeName, 'destroy') || str_contains($routeName, 'delete')) {
                // Kecualikan route logout
                if ($routeName !== 'logout') {
                    abort(403, 'Akses Ditolak: Role Admin tidak diizinkan untuk menghapus data.');
                }
            }
        }

        return $next($request);
    }
}
