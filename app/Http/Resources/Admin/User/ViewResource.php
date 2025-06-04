<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin\User;

use App\Enums\DeficiencyStatusEnum;
use App\Enums\InspectionStatusEnum;
use App\Models\User;
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
        /** @var User $user */
        $user = $this;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'dob' => Carbon::createFromFormat('d/m/Y', $user->dob)->format('d M Y'),
            'pf_no' => $user->pf_no,
            'mobile_no' => $user->mobile_no,
            'current_designation' => $user->activeDesignation,
            'inspection_statistics' => [
                'completed' => $user->inspections_completed_count,
                'total' => $user->inspections_attended_count,
                'progress' => $user->inspections_in_progress_count,
            ],
            'deficiency_statistics' => [
                'attended' => $user->deficiencies_attended_count,
                'seen' => $user->deficiencies_seen_count,
                'pending' => $user->deficiencies_in_pending_count,
                'total' => $user->deficiencies_total_count,
            ],
            'recent_inspections' => $user->recentInspections->map(function ($inspection) {
                return [
                    'id' => $inspection->id,
                    'datetime' => $inspection->datetime->format('d M Y H:i'),
                    'location' => Str::limit($inspection->location, 10),
                    'time' => $inspection->datetime->format('H:i'),
                    'status' => InspectionStatusEnum::fromValue($inspection->status)->description,
                ];
            }),
            'recent_deficiencies' => $user->recentDeficiencies->map(function ($deficiency) {
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
