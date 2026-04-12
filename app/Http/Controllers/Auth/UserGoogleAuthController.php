<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserDeviceVerificationNotification;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserGoogleAuthController extends Controller
{
    public function __construct(
        private readonly TrustedDeviceService $trustedDeviceService
    ) {
    }

    public function redirect(Request $request): RedirectResponse
    {
        $clientId = (string) config('services.google.client_id');
        $redirectUri = (string) config('services.google.redirect');

        if ($clientId === '' || $redirectUri === '') {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Konfigurasi Google OAuth belum lengkap di server.',
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
            'access_type' => 'online',
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request): RedirectResponse
    {
        $savedState = (string) $request->session()->pull('google_oauth_state', '');
        $incomingState = (string) $request->query('state', '');

        if ($savedState === '' || ! hash_equals($savedState, $incomingState)) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'State OAuth tidak valid. Coba login Google lagi.',
            ]);
        }

        if ($request->filled('error')) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Login Google dibatalkan.',
            ]);
        }

        $code = (string) $request->query('code', '');
        if ($code === '') {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Kode otorisasi Google tidak ditemukan.',
            ]);
        }

        $tokenPayload = $this->exchangeCodeForToken($code);
        if (! $tokenPayload || empty($tokenPayload['id_token'])) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Gagal mengambil token dari Google.',
            ]);
        }

        $idTokenPayload = $this->verifyGoogleIdToken((string) $tokenPayload['id_token']);
        if (! $idTokenPayload) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Token Google tidak valid.',
            ]);
        }

        $clientId = (string) config('services.google.client_id');
        if (($idTokenPayload['aud'] ?? null) !== $clientId) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Token Google tidak cocok dengan aplikasi ini.',
            ]);
        }

        $email = isset($idTokenPayload['email']) ? strtolower((string) $idTokenPayload['email']) : '';
        $sub = (string) ($idTokenPayload['sub'] ?? '');
        $name = (string) ($idTokenPayload['name'] ?? 'User');
        $picture = isset($idTokenPayload['picture']) ? (string) $idTokenPayload['picture'] : null;
        $emailVerified = filter_var($idTokenPayload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($email === '' || $sub === '' || ! $emailVerified) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Akun Google tidak valid atau email belum terverifikasi.',
            ]);
        }

        $userByGoogle = User::query()
            ->where('google_id', $sub)
            ->first();

        $userByEmail = User::query()
            ->whereEmailInsensitive($email)
            ->first();

        if (! $userByGoogle && $userByEmail && ! $userByEmail->google_id) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Email ini sudah terdaftar dengan metode password. Silakan login pakai email/password.',
            ]);
        }

        if (! $userByGoogle && $userByEmail && $userByEmail->google_id && $userByEmail->google_id !== $sub) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Email ini sudah terhubung dengan akun Google lain.',
            ]);
        }

        $user = $userByGoogle ?? null;

        if ($user && ($user->is_admin || $user->hasRole('admin'))) {
            return redirect()->route('user.login')->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }

        if (! $user) {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'google_id' => $sub,
                'google_avatar' => $picture,
                'avatar' => $picture,
                'email_verified_at' => null,
                'is_admin' => false,
                'is_frozen' => false,
            ]);

            $user->sendEmailVerificationNotification();
        } else {
            $updates = [];

            if ($picture) {
                $updates['google_avatar'] = $picture;

                if (! $user->hasUploadedAvatar()) {
                    $updates['avatar'] = $picture;
                }
            }

            if (! empty($updates)) {
                $user->forceFill($updates)->save();
            }

            if (! $user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
            }
        }

        if ($user->is_frozen) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Akun Anda sedang dibekukan. Silakan hubungi admin.',
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            $isTrustedDevice = $this->trustedDeviceService->touchIfTrusted($user, $request);

            if (! $isTrustedDevice) {
                if ($this->trustedDeviceService->hasTrustedDevice($user)) {
                    $challenge = $this->trustedDeviceService->createChallenge($user, $request, true, route('user.panel', absolute: false));
                    $verificationUrl = route('user.device.verify', ['token' => $challenge->token]);
                    $user->notify(new UserDeviceVerificationNotification($verificationUrl));

                    return redirect()->route('user.login')->with('status', 'Login Google dari device baru terdeteksi. Kami sudah kirim link verifikasi ke email Anda.');
                }

                $this->trustedDeviceService->trustCurrentDevice($user, $request);
            }
        }

        $this->ensureUserRole($user);

        Auth::login($user, true);
        $request->session()->regenerate();

        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('status', 'Link verifikasi email sudah dikirim. Silakan cek inbox Anda.');
        }

        $defaultRedirect = route('user.panel', absolute: false);
        return redirect()->intended($defaultRedirect);
    }

    private function exchangeCodeForToken(string $code): ?array
    {
        try {
            $response = Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => (string) config('services.google.client_id'),
                'client_secret' => (string) config('services.google.client_secret'),
                'redirect_uri' => (string) config('services.google.redirect'),
                'grant_type' => 'authorization_code',
            ]);
        } catch (\Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();
        return is_array($payload) ? $payload : null;
    }

    private function verifyGoogleIdToken(string $idToken): ?array
    {
        try {
            $response = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $idToken,
            ]);
        } catch (\Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();
        return is_array($payload) ? $payload : null;
    }

    private function ensureUserRole(User $user): void
    {
        if (! method_exists($user, 'syncRoles')) {
            return;
        }

        if (! Schema::hasTable('roles') || ! Schema::hasTable('model_has_roles')) {
            return;
        }

        Role::findOrCreate('user', 'web');
        $user->syncRoles(['user']);
    }
}
