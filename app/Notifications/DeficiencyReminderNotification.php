<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Deficiency;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeficiencyReminderNotification extends Notification
{
    use Queueable;

    public function __construct(private Deficiency $deficiency)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Reminder: Action Required for Reported Deficiency')
            ->markdown('emails.deficiency-reminder', [
                'deficiency' => $this->deficiency,
                'notifiable' => $notifiable
            ]);
    }
}
