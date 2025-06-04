<?php

declare(strict_types=1);

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        switch (true) {
            case $media->collection_name === 'deficiency_photos'
            && $media->model_type === \App\Models\Deficiency::class:
                return 'deficiencies/';

            case $media->model_type === \App\Models\TemporaryUpload::class:
                return 'temporary_uploads/';

            default:
                return $media->id . '/';
        }
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive-images/';
    }
}
