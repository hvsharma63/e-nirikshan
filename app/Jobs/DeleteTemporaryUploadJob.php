<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\TemporaryUpload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteTemporaryUploadJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        TemporaryUpload::where('created_at', '<', now()->subDay())
        ->chunkById(100, function ($uploads) {
            foreach ($uploads as $upload) {
                $upload->delete();
            }
        });
    }
}
