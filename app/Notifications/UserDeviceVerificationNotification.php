<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDeviceVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $verificationUrl
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Login Device Baru - Kataloque')
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Kami mendeteksi login dari device atau browser baru.')
            ->line('Untuk melanjutkan login, verifikasi device ini lewat tombol berikut.')
            ->action('Verifikasi Device', $this->verificationUrl)
            ->line('Link ini berlaku 15 menit dan hanya bisa dipakai sekali.')
            ->line('Jika ini bukan Anda, abaikan email ini dan segera ganti password.');
    }
}
