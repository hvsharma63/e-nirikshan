<?php

declare(strict_types=1);

namespace App\Http\Resources\Officer\Deficiency;

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
            'location' => $this->inspection->location,
            'note' => Str::limit($this->note, 50),
            'action_date' => $this->action_date ? Carbon::parse($this->action_date)->format('d M Y') : '-',
            'attended_by' => $this->inspection->attendedBy->name,
            'date' => Carbon::parse($this->inspection->datetime)->format('d M Y'),
            'time' => Carbon::parse($this->inspection->datetime)->format('H:i'),
            'status' => DeficiencyStatusEnum::fromValue($this->status)->description,
        ];
    }
}
