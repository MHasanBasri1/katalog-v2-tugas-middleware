<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserAuthController extends Controller
{
    public function __construct()
    {
    }

    public function showLoginForm(): View
    {
        return view('auth.user-login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        $isAdmin = $user && ($user->is_admin || $user->hasRole('admin'));

        if ($isAdmin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => trans('auth.failed'),
            ])->onlyInput('email');
        }



        $defaultRedirect = $user && ! $user->hasVerifiedEmail()
            ? route('verification.notice', absolute: false)
            : route('user.panel', absolute: false);

        $intended = $request->session()->get('url.intended');
        $intendedPath = is_string($intended) ? (parse_url($intended, PHP_URL_PATH) ?: '') : '';

        if ($user && str_starts_with($intendedPath, '/admin')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended($defaultRedirect);
    }

    public function showRegisterForm(): View
    {
        return view('auth.user-register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'regex:/^.+@.+\..+$/', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $normalizedEmail = User::normalizeEmail((string) $validated['email']);

        if (User::query()->whereEmailInsensitive($normalizedEmail)->exists()) {
            return back()
                ->withErrors(['email' => 'Email sudah terdaftar. Silakan gunakan email lain atau login.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $normalizedEmail,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => config('auth.verification.required') ? null : now(),
        ]);

        event(new Registered($user));
        $this->ensureDefaultUserRole($user);

        Auth::login($user);

        $request->session()->regenerate();

        if (config('auth.verification.required')) {
            return redirect()->route('verification.notice')->with('status', 'Akun berhasil dibuat. Cek email untuk verifikasi.');
        }

        return redirect()->route('user.panel')->with('status', 'Akun berhasil dibuat dan otomatis terverifikasi.');
    }

    private function ensureDefaultUserRole(User $user): void
    {
        Role::findOrCreate('member', 'web');
        $user->syncRoles(['member']);
    }
}
