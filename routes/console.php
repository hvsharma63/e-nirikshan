<?php

declare(strict_types=1);

use App\Jobs\DeficiencyReminderScheduledJob;
use App\Jobs\DeleteTemporaryUploadJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new DeficiencyReminderScheduledJob())->dailyAt('10:00');
Schedule::job(new DeficiencyReminderScheduledJob())->dailyAt('16:00');
Schedule::job(new DeleteTemporaryUploadJob())->dailyAt('00:10');
Schedule::command('db:dump-and-email')->dailyAt('01:00');
