<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->first();

        if (! $user || ! $user->is_admin) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email ini tidak terdaftar sebagai akun admin.']);
        }

        $token = app('auth.password.broker')->createToken($user);
        $user->notify(new AdminResetPasswordNotification($token));

        return back()->with('status', 'Link reset password admin berhasil dikirim ke email Anda.');
    }
}
