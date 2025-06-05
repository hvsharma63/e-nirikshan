<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'pf_no' => $user->pf_no,
            'mobile_no' => $user->mobile_no,
            'designation' => $user->activeDesignation?->address_asc,
            'deficiencies_attended_count' => $user->deficiencies_attended_count,
            'inspections_attended_count' => $user->inspections_attended_count,
        ];
    }
}
