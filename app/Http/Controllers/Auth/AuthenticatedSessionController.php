<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $intended = $request->session()->get('url.intended');
        $intendedPath = is_string($intended) ? (parse_url($intended, PHP_URL_PATH) ?: '') : '';

        // If intended path is NOT an admin path, clear it to avoid 403 Forbidden
        // because admins typically don't have the 'user' role for member dashboards.
        if ($intendedPath && ! str_starts_with($intendedPath, '/admin')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout from all possible guards or at least ensure the default one is cleared
        Auth::logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
