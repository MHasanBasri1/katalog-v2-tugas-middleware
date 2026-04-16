<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends BaseApiController
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'google_avatar' => $user->google_avatar,
            'email_verified_at' => optional($user->email_verified_at)->toISOString(),
            'created_at' => optional($user->created_at)->toISOString(),
            'updated_at' => optional($user->updated_at)->toISOString(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        if ($validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'google_avatar' => $user->google_avatar,
            'email_verified_at' => optional($user->email_verified_at)->toISOString(),
            'updated_at' => optional($user->updated_at)->toISOString(),
        ], 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $newPath = $request->file('avatar')->store("avatars/users/{$user->id}", 'public');

        if ($user->hasUploadedAvatar()) {
            Storage::disk('public')->delete((string) $user->avatar);
        }

        $user->forceFill([
            'avatar' => $newPath,
        ])->save();

        return $this->success([
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'google_avatar' => $user->google_avatar,
        ], 'Avatar berhasil diperbarui.');
    }

    public function destroyAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasUploadedAvatar()) {
            Storage::disk('public')->delete((string) $user->avatar);
        }

        $user->forceFill([
            'avatar' => $user->google_avatar,
        ])->save();

        return $this->success([
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
            'google_avatar' => $user->google_avatar,
        ], 'Avatar berhasil dihapus.');
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check((string) $validated['current_password'], (string) $user->password)) {
            return $this->error('Password saat ini tidak sesuai.', 422, [
                'current_password' => ['Password saat ini tidak sesuai.'],
            ]);
        }

        $user->update([
            'password' => Hash::make((string) $validated['password']),
        ]);

        return $this->success(null, 'Password berhasil diperbarui.');
    }
}
