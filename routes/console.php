<?php

use App\Jobs\DeficiencyReminderScheduledJob;
use Illuminate\Support\Facades\Schedule;


Schedule::job(new DeficiencyReminderScheduledJob)->dailyAt('10:00');
Schedule::job(new DeficiencyReminderScheduledJob)->dailyAt('16:00');