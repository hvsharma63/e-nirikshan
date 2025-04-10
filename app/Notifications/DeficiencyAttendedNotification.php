<?php

namespace App\Notifications;

use App\Models\Deficiency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeficiencyAttendedNotification extends Notification
{
    use Queueable;

    protected Deficiency $deficiency;

    public function __construct(Deficiency $deficiency)
    {
        $this->deficiency = $deficiency;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Deficiency Attended')
            ->action('View Deficiency', url('/deficiencies/' . $this->deficiency->id))
            ->line('Thank you for using Ghaat-Nirikshan!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'deficiency_id' => $this->deficiency->id,
        ];
    }
}
