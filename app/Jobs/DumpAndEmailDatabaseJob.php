<?php 

namespace App\Jobs;

use App\Notifications\DatabaseDumpNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\DbDumper\Databases\MySql;

class DumpAndEmailDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "db-backup-" . config('app.name') . "-". config('app.env') .  "-$timestamp.sql";
        $dumpPath = storage_path("app/backups");
        $fullPath = "$dumpPath/$filename";

        if (!is_dir($dumpPath)) {
            mkdir($dumpPath, 0755, true);
        }

        $db = config('database.connections.mysql');

        try {
            MySql::create()
                ->setDbName($db['database'])
                ->setUserName($db['username'])
                ->setPassword($db['password'])
                ->dumpToFile($fullPath);
        } catch (\Exception $e) {
            Log::error("DB Dump failed: " . $e->getMessage());
            return;
        }

        try {
            $emailAddress = config('app.database_backup_to_email_address');

            Notification::route('mail', $emailAddress)
                ->notify(new DatabaseDumpNotification($fullPath, $filename));
        } catch (\Exception $e) {
            Log::error("Mail sending failed: " . $e->getMessage());
        }

        // Clean up
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
