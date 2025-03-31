<?php

namespace App\Queries;

use App\Enums\DeficiencyStatusEnum;
use App\Models\Deficiency;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DeficiencyQueries {

    public function __construct() {
    }

    public function list(int $userId): Collection {
        return Deficiency::query()
            ->withOnly(['inspection:id,location,attended_by,datetime','inspection.attendedBy:id,name'])
            ->select(['id', 'inspection_id', 'note', 'action_date', 'status'])
            ->where('pertains_to', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function get(int $deficiencyId): ?Deficiency {
        return Deficiency::query()
            ->withOnly(['pertainsTo:id,name,email'])
            ->findOrFail($deficiencyId);
    }

    public function view(int $deficiencyId): ?Deficiency {
        return Deficiency::query()
            ->withOnly([
                'inspection:id,location,address,attended_by,datetime,note,day_period,status',
                'inspection.attendedBy:id,name,designation',
                'comment:id,deficiency_id,comment_by,comment'
            ])
            ->select(['id', 'inspection_id','pertains_to', 'note', 'status','action_date', 'created_at'])
            ->findOrFail($deficiencyId);
    }

    public function updateStatus(int $deficiencyId, int $status): void {
        Deficiency::query()
            ->where('id', $deficiencyId)
            ->update([
                'status' => $status
            ]);
    }

    public function updateStatusAndActionDate(int $deficiencyId, DeficiencyStatusEnum $status, Carbon $actionDate): void {
        Deficiency::query()
            ->where('id', $deficiencyId)
            ->update([
                'action_date' => $actionDate,
                'status' => $status
            ]);
    }

    public function getWhere(int $inspectionId): Collection {
        return Deficiency::query()
            ->select(['id', 'inspection_id', 'status'])
            ->where('inspection_id', $inspectionId)
            ->get();
    }
}