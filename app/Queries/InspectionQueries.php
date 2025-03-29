<?php

namespace App\Queries;

use App\Models\Inspection;
use App\Models\User;
use Illuminate\Support\Collection;

class InspectionQueries {

    public function __construct() {
    }

    public function create(array $inspectionData): Inspection {  
        return Inspection::query()
            ->create($inspectionData);
    }

    public function list(int $userId): Collection {
        return Inspection::query()
            ->select(['id', 'location', 'datetime', 'day_period', 'status'])
            ->withCount(['deficiencies'])
            ->where('attended_by', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function get(int $inspectionId, int $userId): ?Inspection {
        return Inspection::query()
            ->where('attended_by', $userId)
            ->with(['deficiencies'])
            ->findOrFail( $inspectionId );
    }
}