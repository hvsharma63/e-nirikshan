<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin\User;

use App\Enums\DeficiencyStatusEnum;
use App\Enums\InspectionStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'name' => $this->name,
            'email' => $this->email,
            'dob' => Carbon::createFromFormat('d/m/Y', $this->dob)->format('d M Y'),
            'pf_no' => $this->pf_no,
            'mobile_no' => $this->mobile_no,
            'current_designation' => $this->activeDesignation,
            'inspection_statistics' => [
                'completed' => $this->inspections_completed_count,
                'total' => $this->inspections_attended_count,
                'progress' => $this->inspections_in_progress_count,
            ],
            'deficiency_statistics' => [
                'attended' => $this->deficiencies_attended_count,
                'seen' => $this->deficiencies_seen_count,
                'pending' => $this->deficiencies_in_pending_count,
                'total' => $this->deficiencies_total_count,
            ],
            'recent_inspections' => $this->recentInspections->map(function ($inspection) {
                return [
                    'id' => $inspection->id,
                    'datetime' => $inspection->datetime->format('d M Y H:i'),
                    'location' => Str::limit($inspection->location, 10),
                    'time' => $inspection->datetime->format('H:i'),
                    'status' => InspectionStatusEnum::fromValue($inspection->status)->description,
                ];
            }),
            'recent_deficiencies' => $this->recentDeficiencies->map(function ($deficiency) {
                return [
                    'id' => $deficiency->id,
                    'note' => $deficiency->note,
                    'created_at' => $deficiency->created_at->format('d M Y H:i'),
                    'status' => DeficiencyStatusEnum::fromValue($deficiency->status)->description,
                ];
            }),
        ];
    }
}
