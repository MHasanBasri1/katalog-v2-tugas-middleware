<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends BaseApiController
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
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

        $user = User::query()->where('email', $validated['email'])->first();

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

    public function me(Request $request): JsonResponse
    {
        return $this->success($this->transformUser($request->user()));
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
            'email_verified_at' => optional($user->email_verified_at)->toISOString(),
            'is_frozen' => (bool) $user->is_frozen,
            'created_at' => optional($user->created_at)->toISOString(),
        ];
    }
}
