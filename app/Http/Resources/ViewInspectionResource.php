<?php

namespace App\Http\Resources;

use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
use App\Http\Resources\Inspection\ItemDeficiencyResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewInspectionResource extends JsonResource
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
            'location' => $this->location,
            'attended_by' => $this->attendedBy->name,
            'datetime' => Carbon::parse($this->datetime)->format('d M Y H:i A'),
            'day_period' => InspectionDayPeriodEnum::fromValue($this->day_period)->description,
            'no_deficiencies_found' => $this->no_deficiencies_found,
            'status' => InspectionStatusEnum::fromValue($this->status)->description,
            'deficiencies' => ItemDeficiencyResource::collection($this->deficiencies),
        ];
    }
}
