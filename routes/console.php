<?php

use App\Jobs\DeficiencyReminderScheduledJob;
use App\Queries\DeficiencyQueries;
use Illuminate\Support\Facades\Schedule;

$deficiencyQueries = new DeficiencyQueries();

Schedule::job(new DeficiencyReminderScheduledJob($deficiencyQueries))->dailyAt('10:00');
Schedule::job(new DeficiencyReminderScheduledJob($deficiencyQueries))->dailyAt('16:00');