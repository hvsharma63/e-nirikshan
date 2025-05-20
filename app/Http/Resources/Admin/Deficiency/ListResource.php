<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin\Deficiency;

use App\Enums\DeficiencyStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'location' => Str::limit($this->inspection->location, 25),
            'note' => Str::limit($this->note, 25),
            'attended_by' => $this->inspection->attendedBy->name . ' (' . $this->inspection->attendedBy->activeDesignation->address_asc . ')',
            'pertains_to' => $this->pertainsTo->name . ' (' . $this->pertainsTo->activeDesignation->address_asc . ')',
            'action_date' => $this->action_date ? Carbon::parse($this->action_date)->format('d M Y') : '-',
            'date' => Carbon::parse($this->inspection->datetime)->format('d M Y'),
            'time' => Carbon::parse($this->inspection->datetime)->format('H:i'),
            'status' => DeficiencyStatusEnum::fromValue($this->status)->description,
        ];
    }
}
