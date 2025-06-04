<?php

declare(strict_types=1);

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'  => $this->id,
            'url' => URL::temporarySignedRoute(
                'media.view',
                now()->addMinutes(5),
                ['media' => $this->id],
            ),
        ];
    }
}
