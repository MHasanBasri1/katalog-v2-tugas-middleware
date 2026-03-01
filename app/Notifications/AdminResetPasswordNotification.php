<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class AdminResetPasswordNotification extends ResetPassword
{
    protected function resetUrl($notifiable): string
    {
        return url(route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
