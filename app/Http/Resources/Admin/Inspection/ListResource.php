<?php

namespace App\Http\Resources\Admin\Inspection;

use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => Str::limit($this->location, 20),
            'attended_by' => $this->attendedBy->name . ' (' . $this->attendedBy->activeDesignation->address_asc . ')',
            'deficiencies_count' => $this->deficiencies_count,
            'date' => Carbon::parse($this->datetime)->format('d M Y'),
            'time' => Carbon::parse($this->datetime)->format('H:i A'),
            'day_period' => InspectionDayPeriodEnum::fromValue($this->day_period)->description,
            'status' => InspectionStatusEnum::fromValue($this->status)->description,
        ];
    }
}
