<?php

namespace App\Http\Resources\Deficiency;

use App\Enums\DeficiencyStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ListDeficiencyResource extends JsonResource
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
            'time' => Carbon::parse($this->inspection->datetime)->format('H:i A'),
            'status' => DeficiencyStatusEnum::fromValue($this->status)->description,
        ];
    }
}
