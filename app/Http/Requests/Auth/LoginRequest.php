<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'regex:/^.+@.+\..+$/'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::query()
            ->whereEmailInsensitive((string) $this->input('email'))
            ->first();

        if (! $user || ! Hash::check((string) $this->input('password'), (string) $user->password)) {
            RateLimiter::hit($this->throttleKey());
            $this->ensureIsNotRateLimited(); // Langsung cek — pesan muncul tepat di percobaan ke-N

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        if ($user && $user->is_frozen) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            $this->ensureIsNotRateLimited();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang dibekukan. Silakan hubungi admin.',
            ]);
        }

        $this->syncUserRole($user);

        // Only block non-admin roles from accessing the admin panel
        $userRole = $user->roles->first()->name ?? ($user->is_admin ? 'admin' : 'member');
        $isAdminPanelRole = in_array($userRole, ['developer', 'super admin', 'admin']);
        if ($this->is('admin/*') && $user && ! $isAdminPanelRole) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            $this->ensureIsNotRateLimited();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => User::normalizeEmail((string) $this->input('email')),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $roleName = $this->getRoleName();
        $maxAttempts = in_array($roleName, ['developer', 'super admin']) ? 3 : 5;

        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $roleDisplay = Str::title($roleName);

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Untuk keamanan $roleDisplay, akun dikunci selama " . ceil($seconds / 60) . " menit.",
        ]);
    }

    private function getRoleName(): string
    {
        $user = User::query()->whereEmailInsensitive((string) $this->input('email'))->first();
        if ($user) {
            return $user->roles->first()->name ?? ($user->is_admin ? 'admin' : 'member');
        }
        return 'guest';
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    private function syncUserRole($user): void
    {
        if (! $user || ! method_exists($user, 'syncRoles')) {
            return;
        }

        if (! Schema::hasTable('roles') || ! Schema::hasTable('model_has_roles')) {
            return;
        }

        // Only auto-assign role for users who have NO Spatie role yet.
        // Never override an existing role (developer, super admin, etc.).
        if ($user->roles->isEmpty()) {
            $expectedRole = $user->is_admin ? 'admin' : 'member';
            Role::findOrCreate($expectedRole, 'web');
            $user->syncRoles([$expectedRole]);
        }
    }
}
