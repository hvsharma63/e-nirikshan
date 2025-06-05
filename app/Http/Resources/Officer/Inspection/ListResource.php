<?php

declare(strict_types=1);

namespace App\Http\Resources\Officer\Inspection;

use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'deficiencies_count' => $this->deficiencies_count,
            'date' => Carbon::parse($this->datetime)->format('d M Y'),
            'time' => Carbon::parse($this->datetime)->format('H:i'),
            'day_period' => InspectionDayPeriodEnum::fromValue($this->day_period)->description,
            'status' => InspectionStatusEnum::fromValue($this->status)->description,
        ];
    }
}
