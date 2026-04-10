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
                'user_id' => $event->user->id,
                'description' => "{$event->user->name} telah logout dari sistem",
                'activity_type' => 'auth',
                'icon' => 'ti-logout',
                'color' => 'orange',
            ]);
        }
    }
}
