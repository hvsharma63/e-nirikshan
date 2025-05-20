<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

class TemporaryUpload extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['uuid', 'user_id'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('temporary_uploads')
                ->useDisk('private')
                ->acceptsFile(function (File $file) {
                    return in_array($file->mimeType, [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'application/pdf',
                        'application/msword', // .doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                    ]);
                });
    }

    protected static function booted()
    {
        static::deleting(function (self $upload) {
            $upload->clearMediaCollection('temporary_uploads');
        });
    }
}
