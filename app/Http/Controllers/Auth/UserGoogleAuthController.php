<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class UserGoogleAuthController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Redirect to Google OAuth.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google Callback.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Gagal mengambil data dari Google. Silakan coba lagi.',
            ]);
        }

        $email = strtolower((string) $googleUser->getEmail());
        $sub = (string) $googleUser->getId();
        $name = (string) $googleUser->getName();
        $avatar = (string) $googleUser->getAvatar();

        if ($email === '' || $sub === '') {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Informasi akun Google tidak lengkap.',
            ]);
        }

        // 1. Cari user berdasarkan google_id
        $user = User::query()->where('google_id', $sub)->first();

        // 2. Jika tidak ada, cari berdasarkan email
        if (! $user) {
            $userByEmail = User::query()
                ->whereEmailInsensitive($email)
                ->first();

            if ($userByEmail) {
                if (! $userByEmail->google_id) {
                    return redirect()->route('user.login')->withErrors([
                        'email' => 'Email ini sudah terdaftar dengan password. Silakan login manual atau gunakan fitur lupa password.',
                    ]);
                }
                
                if ($userByEmail->google_id !== $sub) {
                    return redirect()->route('user.login')->withErrors([
                        'email' => 'Email ini sudah terhubung dengan akun Google lain.',
                    ]);
                }
                
                $user = $userByEmail;
            }
        }

        // 3. Security Check: Admin cannot login through public user login
        if ($user && ($user->is_admin || $user->hasRole('admin'))) {
            return redirect()->route('user.login')->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }

        // 4. Register new user if not found
        if (! $user) {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'google_id' => $sub,
                'google_avatar' => $avatar,
                'avatar' => $avatar,
                'email_verified_at' => now(), // Auto-verify for Google login
                'is_admin' => false,
                'is_frozen' => false,
            ]);
        } else {
            // Update existing user data if needed
            $updates = [];
            if ($user->google_avatar !== $avatar) {
                $updates['google_avatar'] = $avatar;
                if (! $user->hasUploadedAvatar()) {
                    $updates['avatar'] = $avatar;
                }
            }

            if (! empty($updates)) {
                $user->forceFill($updates)->save();
            }
        }

        // 5. Account Frozen check
        if ($user->is_frozen) {
            return redirect()->route('user.login')->withErrors([
                'email' => 'Akun Anda sedang dibekukan. Silakan hubungi admin.',
            ]);
        }

        // 6. Trusted Device Logic


        // 7. Ensure Role & Login
        $this->ensureUserRole($user);

        Auth::login($user, true);
        $request->session()->regenerate();

        $defaultRedirect = route('user.panel', absolute: false);
        return redirect()->intended($defaultRedirect);
    }

    private function ensureUserRole(User $user): void
    {
        if (! method_exists($user, 'syncRoles')) {
            return;
        }

        if (! Schema::hasTable('roles') || ! Schema::hasTable('model_has_roles')) {
            return;
        }

        Role::findOrCreate('member', 'web');
        $user->syncRoles(['member']);
    }
}
