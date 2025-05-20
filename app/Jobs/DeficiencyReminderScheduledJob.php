<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Notifications\DeficiencyReminderNotification;
use App\Queries\DeficiencyQueries;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class DeficiencyReminderScheduledJob implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(DeficiencyQueries $deficiencyQueries): void
    {
        // Resolve the dependency at runtime.
        $deficiencies = $deficiencyQueries->getPendingDeficiencies();

        foreach ($deficiencies as $deficiency) {
            $deficiency->pertainsTo->notify(new DeficiencyReminderNotification($deficiency));
        }
    }
}
