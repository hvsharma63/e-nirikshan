<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DatabaseDumpNotification extends Notification
{
    public string $path;
    public string $filename;

    public function __construct(string $path, string $filename)
    {
        $this->path = $path;
        $this->filename = $filename;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Daily ' . config('app.name') . '-' . config('app.env') . ' SQL Backup - ' . now()->toDateString())
            ->line('Attached is your daily SQL backup.')
            ->attach($this->path, [
                'as' => $this->filename,
                'mime' => 'application/sql',
            ]);
    }
}
