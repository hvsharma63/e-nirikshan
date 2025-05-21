<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Deficiency;
use App\Models\User;
use App\Notifications\DeficiencyReportedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDeficiencyNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Deficiency $deficiency;

    public function __construct(Deficiency $deficiency)
    {
        $this->deficiency = $deficiency;
    }

    public function handle(): void
    {
        $this->deficiency->load('pertainsTo');
        $user = $this->deficiency->pertainsTo;

        if ($user instanceof User) {
            $user->notify(new DeficiencyReportedNotification($this->deficiency));
        }
    }
}
