<?php

namespace App\Http\Resources\Officer\Deficiency;

use App\Enums\DeficiencyStatusEnum;
use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewResource extends JsonResource
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
            'inspection_id' => $this->inspection->id,
            'location' => $this->inspection->location,
            'note' => $this->note,
            'attended_by' => $this->inspection->attendedBy,
            'datetime' => Carbon::parse($this->inspection->datetime)->format('d M Y H:i'),
            'deficiency_note' => $this->note,
            'day_period' => InspectionDayPeriodEnum::fromValue($this->inspection->day_period)->description,
            'inspection_status' => InspectionStatusEnum::fromValue($this->inspection->status)->description,
            'deficiency_status' => DeficiencyStatusEnum::fromValue($this->status)->description,
            'action_date' => $this->action_date ? Carbon::parse($this->action_date)->format('d M Y') : null,
            'comment' => $this->comment ? $this->comment->comment : null,
            'deficiency_created_at' => Carbon::parse($this->inspection->datetime)->format('d M Y H:i'),
        ];
    }
}
