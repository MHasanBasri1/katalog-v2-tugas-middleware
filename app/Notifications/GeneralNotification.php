<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    private string $title;
    private string $message;
    private string $type;
    private array $extraData;

    public function __construct(string $title, string $message, string $type = 'info', array $extraData = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->extraData = $extraData;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return array_merge([
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
        ], $this->extraData);
    }
}
