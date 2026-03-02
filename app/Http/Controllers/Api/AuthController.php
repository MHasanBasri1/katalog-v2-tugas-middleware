<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends BaseApiController
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $normalizedEmail = User::normalizeEmail((string) $validated['email']);

        if (User::query()->whereEmailInsensitive($normalizedEmail)->exists()) {
            return $this->error('Email sudah terdaftar.', 422, [
                'email' => ['Email sudah terdaftar. Silakan gunakan email lain atau login.'],
            ]);
        }

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $normalizedEmail,
            'password' => Hash::make((string) $validated['password']),
            'is_admin' => false,
            'is_frozen' => false,
        ]);

        $this->ensureUserRole($user);
        $token = $this->issueToken($user);

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->transformUser($user),
        ], 'Registrasi berhasil.', 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->whereEmailInsensitive((string) $validated['email'])
            ->first();

        if (! $user || ! Hash::check((string) $validated['password'], (string) $user->password)) {
            return $this->error('Email atau password tidak valid.', 422);
        }

        if ($user->is_frozen) {
            return $this->error('Akun sedang dibekukan. Hubungi admin.', 403);
        }

        if ($user->is_admin || $user->hasRole('admin')) {
            return $this->error('Akun admin tidak bisa login melalui API user.', 403);
        }

        $this->ensureUserRole($user);
        $token = $this->issueToken($user);

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->transformUser($user),
        ], 'Login berhasil.');
    }

    public function google(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        $googleClientId = (string) config('services.google.client_id');
        if ($googleClientId === '') {
            return $this->error('Konfigurasi GOOGLE_CLIENT_ID belum diatur pada server.', 500);
        }

        $payload = $this->verifyGoogleIdToken((string) $validated['id_token']);
        if (! $payload) {
            return $this->error('Token Google tidak valid atau sudah kedaluwarsa.', 422);
        }

        if (($payload['aud'] ?? null) !== $googleClientId) {
            return $this->error('Token Google tidak cocok dengan aplikasi ini.', 422);
        }

        $email = isset($payload['email']) ? strtolower((string) $payload['email']) : '';
        $sub = (string) ($payload['sub'] ?? '');
        $name = (string) ($payload['name'] ?? 'User');
        $picture = isset($payload['picture']) ? (string) $payload['picture'] : null;
        $emailVerified = filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($email === '' || $sub === '') {
            return $this->error('Data akun Google tidak lengkap.', 422);
        }

        if (! $emailVerified) {
            return $this->error('Email Google belum terverifikasi.', 422);
        }

        $userByGoogle = User::query()
            ->where('google_id', $sub)
            ->first();

        $userByEmail = User::query()
            ->whereEmailInsensitive($email)
            ->first();

        if (! $userByGoogle && $userByEmail && ! $userByEmail->google_id) {
            return $this->error(
                'Email ini sudah terdaftar dengan metode password. Silakan login pakai email/password.',
                409
            );
        }

        if (! $userByGoogle && $userByEmail && $userByEmail->google_id && $userByEmail->google_id !== $sub) {
            return $this->error('Email ini sudah terhubung dengan akun Google lain.', 409);
        }

        $user = $userByGoogle ?? null;

        if ($user && ($user->is_admin || $user->hasRole('admin'))) {
            return $this->error('Akun admin tidak bisa login melalui API user.', 403);
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
            return $this->error('Akun sedang dibekukan. Hubungi admin.', 403);
        }

        $this->ensureUserRole($user);
        $token = $this->issueToken($user);

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->transformUser($user),
        ], 'Login Google berhasil.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success($this->transformUser($request->user()));
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = User::normalizeEmail((string) $validated['email']);

        $user = User::query()
            ->whereEmailInsensitive($email)
            ->first();

        if ($user && ($user->is_admin || $user->hasRole('admin'))) {
            return $this->error('Akun admin tidak bisa reset password dari API user.', 403);
        }

        $status = Password::sendResetLink([
            'email' => $email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(null, 'Link reset password sudah dikirim ke email Anda.');
        }

        return $this->error('Gagal mengirim link reset password.', 422, [
            'email' => [__($status)],
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = User::normalizeEmail((string) $validated['email']);

        $user = User::query()
            ->whereEmailInsensitive($email)
            ->first();

        if ($user && ($user->is_admin || $user->hasRole('admin'))) {
            return $this->error('Akun admin tidak bisa reset password dari API user.', 403);
        }

        $status = Password::reset(
            [
                'email' => $email,
                'password' => (string) $validated['password'],
                'password_confirmation' => (string) $request->input('password_confirmation'),
                'token' => (string) $validated['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'api_token' => null,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success(null, 'Password berhasil direset. Silakan login ulang.');
        }

        return $this->error('Reset password gagal.', 422, [
            'email' => [__($status)],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->forceFill(['api_token' => null])->save();

        return $this->success(null, 'Logout berhasil.');
    }

    private function issueToken(User $user): string
    {
        $plainToken = Str::random(80);

        $user->forceFill([
            'api_token' => hash('sha256', $plainToken),
        ])->save();

        return $plainToken;
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

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'google_avatar' => $user->google_avatar,
            'email_verified_at' => optional($user->email_verified_at)->toISOString(),
            'is_frozen' => (bool) $user->is_frozen,
            'created_at' => optional($user->created_at)->toISOString(),
        ];
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
}
