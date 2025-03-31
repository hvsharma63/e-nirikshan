<?php

namespace App\Jobs;

use App\Models\Deficiency;
use App\Notifications\DeficiencyReportedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDeficiencyNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Deficiency $deficiency;

    public function __construct(Deficiency $deficiency)
    {
        $this->deficiency = $deficiency;
    }

    public function handle(): void
    {
        if ($this->deficiency->pertainsTo) {
            $this->deficiency->pertainsTo->notify(new DeficiencyReportedNotification($this->deficiency));
        }
    }
}
