<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin\User;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'pf_no' => $this->pf_no,
            'mobile_no' => $this->mobile_no,
            'designation' => $this->activeDesignation->address_asc,
            'deficiencies_attended_count' => $this->deficiencies_attended_count,
            'inspections_attended_count' => $this->inspections_attended_count,
        ];
    }
}
