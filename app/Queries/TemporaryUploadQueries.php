<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\TemporaryUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TemporaryUploadQueries
{
    public function __construct()
    {
    }

    public function upload(TemporaryUpload $temporaryUpload, UploadedFile $uploadedFile): Media
    {
        return $temporaryUpload->addMedia($uploadedFile)
            ->usingFileName(uniqid('temporary_upload_', true) . '-' . Str::uuid() . '.' . $uploadedFile->extension())
            ->preservingOriginal()
            ->toMediaCollection('temporary_uploads');
    }

    public function firstOrCreate(int $userId, ?string $uuid = null): TemporaryUpload
    {
        return TemporaryUpload::firstOrCreate(
            ['uuid' => $uuid, 'user_id' => $userId],
            ['uuid' => (string) Str::uuid()]
        );
    }

    public function getTemporaryUploads(string $uuid, int $userId): ?TemporaryUpload
    {
        return TemporaryUpload::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->first();
    }

}
