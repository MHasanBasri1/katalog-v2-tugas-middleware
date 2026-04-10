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
        // But the user said "hanya user dan admin aja" (guest gausah)
        
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => "{$user->name} berhasil login ke dalam sistem",
            'activity_type' => 'auth',
            'icon' => 'ti-login',
            'color' => 'emerald',
        ]);
    }
}
