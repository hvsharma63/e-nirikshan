<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\DbDumper\Databases\MySql;
use Carbon\Carbon;

class DumpAndEmailDatabase extends Command
{
    protected $signature = 'db:dump-and-email';
    protected $description = 'Dump DB using Spatie DB Dumper and email the .sql file';

    public function handle()
    {
        $this->info('Creating SQL dump...');

        $timestamp = now()->format('Y-m-d_His');
        $filename = "db-backup-$timestamp.sql";
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
                ->setHost($db['host'])
                ->dumpToFile($fullPath);
        } catch (\Exception $e) {
            $this->error("Dump failed: " . $e->getMessage());
            return;
        }

        $this->info("Dump created at $fullPath");

        Mail::raw("Attached is your daily SQL backup.", function ($message) use ($fullPath, $filename) {
            $message->to(env('BACKUP_TO_EMAIL_ADDRESS'))
                ->subject('Daily '. env('APP_NAME') . 'SQL Backup - ' . Carbon::now()->toDateString())
                ->attach($fullPath, ['as' => $filename]);
        });

        $this->info("Email sent successfully.");

        // Optional cleanup
        unlink($fullPath);
    }
}
