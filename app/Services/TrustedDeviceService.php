<?php

namespace App\Services;

use App\Models\LoginDeviceChallenge;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrustedDeviceService
{
    public function hasTrustedDevice(User $user): bool
    {
        return TrustedDevice::query()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function touchIfTrusted(User $user, Request $request): bool
    {
        $fingerprint = $this->fingerprint($request);

        $device = TrustedDevice::query()
            ->where('user_id', $user->id)
            ->where('fingerprint', $fingerprint)
            ->first();

        if (! $device) {
            return false;
        }

        $device->forceFill([
            'user_agent' => $this->normalizedUserAgent($request),
            'ip_address' => $request->ip(),
            'last_used_at' => now(),
        ])->save();

        return true;
    }

    public function trustCurrentDevice(User $user, Request $request): TrustedDevice
    {
        return TrustedDevice::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'fingerprint' => $this->fingerprint($request),
            ],
            [
                'user_agent' => $this->normalizedUserAgent($request),
                'ip_address' => $request->ip(),
                'last_used_at' => now(),
            ]
        );
    }

    public function createChallenge(User $user, Request $request, bool $remember = false, ?string $intendedPath = null): LoginDeviceChallenge
    {
        $this->cleanupExpiredChallenges($user);

        return LoginDeviceChallenge::query()->create([
            'user_id' => $user->id,
            'token' => Str::random(80),
            'ip_address' => $request->ip(),
            'user_agent' => $this->normalizedUserAgent($request),
            'remember' => $remember,
            'intended_path' => $this->sanitizeIntendedPath($intendedPath),
            'expires_at' => now()->addMinutes(15),
        ]);
    }

    private function cleanupExpiredChallenges(User $user): void
    {
        LoginDeviceChallenge::query()
            ->where('user_id', $user->id)
            ->where(function ($query): void {
                $query->whereNotNull('used_at')
                    ->orWhere('expires_at', '<', now());
            })
            ->delete();
    }

    public function sanitizeIntendedPath(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        if (str_starts_with($path, '/admin')) {
            return null;
        }

        return str_starts_with($path, '/') ? $path : null;
    }

    private function fingerprint(Request $request): string
    {
        $userAgent = $this->normalizedUserAgent($request);
        $acceptLanguage = Str::lower(trim((string) $request->header('Accept-Language', '')));

        return hash('sha256', $userAgent.'|'.$acceptLanguage);
    }

    private function normalizedUserAgent(Request $request): ?string
    {
        $agent = trim((string) $request->userAgent());

        return $agent !== '' ? Str::limit($agent, 512, '') : null;
    }
}
