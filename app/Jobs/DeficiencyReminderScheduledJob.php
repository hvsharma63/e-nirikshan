<?php

namespace App\Jobs;

use App\Notifications\DeficiencyReminderNotification;
use App\Queries\DeficiencyQueries;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeficiencyReminderScheduledJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private DeficiencyQueries $deficiencyQueries)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $deficiencies = $this->deficiencyQueries->getPendingDeficiencies();

        foreach ($deficiencies as $deficiency) {
            $deficiency->pertainsTo->notify(new DeficiencyReminderNotification($deficiency));
        }
        
    }
}
