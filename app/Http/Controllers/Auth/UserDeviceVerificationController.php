<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginDeviceChallenge;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDeviceVerificationController extends Controller
{
    public function __invoke(string $token, Request $request, TrustedDeviceService $trustedDeviceService): RedirectResponse
    {
        $challenge = LoginDeviceChallenge::query()
            ->with('user')
            ->where('token', $token)
            ->first();

        if (! $challenge || ! $challenge->isUsable() || ! $challenge->user) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Link verifikasi device tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $user = $challenge->user;
        $isAdmin = $user->is_admin || $user->hasRole('admin');

        $challenge->forceFill(['used_at' => now()])->save();
        $trustedDeviceService->trustCurrentDevice($user, $request);

        Auth::login($user, (bool) $challenge->remember);
        $request->session()->regenerate();

        $defaultRedirect = $isAdmin 
            ? route('admin.dashboard', absolute: false) 
            : route('user.panel', absolute: false);
            
        $redirectPath = $challenge->intended_path ?: $defaultRedirect;

        return redirect()->to($redirectPath)->with('status', 'Device berhasil diverifikasi. Anda sudah login.');
    }
}
