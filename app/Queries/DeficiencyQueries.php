<?php

namespace App\Queries;

use App\Models\Deficiency;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Support\Collection;

class DeficiencyQueries {

    public function __construct() {
    }

    public function list(int $userId): Collection {
        return Deficiency::query()
            ->with(['inspection:id,location,attended_by,datetime','inspection.attendedBy:id,name'])
            ->select(['id', 'inspection_id', 'note', 'action_date', 'status'])
            ->where('pertains_to', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function get(int $deficiencyId): ?Deficiency {
        return Deficiency::query()
            ->with(['pertainsTo:id,name,email'])
            ->findOrFail($deficiencyId);
    }
}