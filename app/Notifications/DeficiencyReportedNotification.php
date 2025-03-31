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
            ->subject('New Deficiency Reported')
            ->line('A new deficiency has been reported that requires your attention.')
            ->line('Location: ' . $this->deficiency->inspection->location)
            ->line('Note: ' . $this->deficiency->note)
            ->action('View Deficiency', url('/deficiencies/' . $this->deficiency->id))
            ->line('Please take necessary action.');
    }
}
