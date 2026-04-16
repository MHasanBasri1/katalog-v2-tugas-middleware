<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Services\TrustedDeviceService;
use App\Notifications\UserDeviceVerificationNotification;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly TrustedDeviceService $trustedDeviceService
    ) {
    }
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

        $user = $request->user();

        // High Security: Trusted Device Check only for Admin
        if ($user && $user->is_admin && $user->hasVerifiedEmail()) {
            $isTrustedDevice = $this->trustedDeviceService->touchIfTrusted($user, $request);

            if (! $isTrustedDevice) {
                if ($this->trustedDeviceService->hasTrustedDevice($user)) {
                    $intended = $request->session()->get('url.intended');
                    $intendedPath = is_string($intended) ? (parse_url($intended, PHP_URL_PATH) ?: null) : null;

                    $challenge = $this->trustedDeviceService->createChallenge(
                        $user,
                        $request,
                        $request->boolean('remember'),
                        $intendedPath
                    );

                    $verificationUrl = route('user.device.verify', ['token' => $challenge->token]);
                    $user->notify(new UserDeviceVerificationNotification($verificationUrl));

                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('status', 'Login dari device baru terdeteksi. Kami sudah kirim link verifikasi ke email utama Anda.');
                }

                $this->trustedDeviceService->trustCurrentDevice($user, $request);
            }
        }

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
