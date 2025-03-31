<?php

namespace App\Notifications;

use App\Models\Deficiency;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeficiencyReportedNotification extends Notification
{
    use Queueable;

    public function __construct(private Deficiency $deficiency)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Important: New Deficiency Report - Action Required')
            ->markdown('emails.deficiency-notification', [
                'deficiency' => $this->deficiency,
                'notifiable' => $notifiable
            ]);
    }
}
