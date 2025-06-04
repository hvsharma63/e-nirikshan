<?php

declare(strict_types=1);

use App\Jobs\DeficiencyReminderScheduledJob;
use App\Jobs\DeleteTemporaryUploadJob;
use App\Jobs\DumpAndEmailDatabaseJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new DeficiencyReminderScheduledJob())->dailyAt('10:00');
Schedule::job(new DeficiencyReminderScheduledJob())->dailyAt('16:00');
Schedule::job(new DeleteTemporaryUploadJob())->dailyAt('00:10');
Schedule::job(new DumpAndEmailDatabaseJob())->dailyAt('01:00');
