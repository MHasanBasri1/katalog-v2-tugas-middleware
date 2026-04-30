<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ActivityLog;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        // Only log for admin users (based on context of earlier logs)
        // or any authenticated user if desired.
        ActivityLog::create([
            'username' => $user->username ?? $user->name ?? $user->email,
            'role' => $user->roles->first()->name ?? ($user->is_admin ? 'admin' : 'member'),
            'activity' => "{$user->name} berhasil login ke dalam sistem",
            'ip_address' => request()->ip(),
        ]);
    }
}
