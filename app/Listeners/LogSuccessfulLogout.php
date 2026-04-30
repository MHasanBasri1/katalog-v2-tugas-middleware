<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;

class LogSuccessfulLogout
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
    public function handle(Logout $event): void
    {
        if ($event->user) {
            ActivityLog::create([
                'username' => $event->user->username ?? $event->user->name ?? $event->user->email,
                'role' => $event->user->roles->first()->name ?? ($event->user->is_admin ? 'admin' : 'member'),
                'activity' => "{$event->user->name} telah logout dari sistem",
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
