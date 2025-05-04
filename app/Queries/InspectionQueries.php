<?php

namespace App\Queries;

use App\Models\Inspection;
use Illuminate\Pagination\LengthAwarePaginator;

class InspectionQueries {

    public function __construct() {
    }

    public function create(array $inspectionData): Inspection {  
        return Inspection::query()
            ->create($inspectionData);
    }

    public function list(int $userId): LengthAwarePaginator {
        return Inspection::query()
            ->select(['id', 'location', 'datetime', 'day_period', 'status'])
            ->withCount(['deficiencies'])
            ->where('attended_by', $userId)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function get(int $inspectionId, int $userId): ?Inspection {
        return Inspection::query()
            ->select(['id','location', 'datetime', 'attended_by', 'day_period', 'no_deficiencies_found', 'status'])
            ->withOnly([
                'attendedBy:id,name',
                'deficiencies.pertainsTo.activeDesignation:id,user_id,address_asc',
                'deficiencies.comment'
            ])
            ->where('attended_by', $userId)
            ->findOrFail($inspectionId);
    }

    public function viewNotePdfByInspectingOfficer(int $inspectionId, int $userId): ?Inspection {
        return Inspection::query()
            ->select(['id','location', 'datetime', 'attended_by', 'day_period', 'no_deficiencies_found', 'status'])
            ->withOnly([
                'attendedBy:id,name',
                'attendedBy.activeDesignation:id,user_id,address_asc',
                'deficiencies.pertainsTo:id,name',
                'deficiencies.pertainsTo.activeDesignation:id,user_id,address_asc',
                'deficiencies:inspection_id,pertains_to,note'
            ])
            ->where('attended_by', $userId)
            ->findOrFail($inspectionId);
    }

    public function viewNoteByPertainingOfficer(int $inspectionId, int $userId): ?Inspection {
        return Inspection::query()
            ->select(['id','location', 'datetime', 'attended_by', 'day_period', 'no_deficiencies_found', 'status'])
            ->withOnly([
                'attendedBy:id,name',
                'deficiencies.pertainsTo' => function($query) use($userId) {
                    $query->select(['id', 'name'])
                        ->withOnly(['activeDesignation:id,user_id,address_asc']);
                },
                'deficiencies:inspection_id,pertains_to,note'
            ])
            ->findOrFail($inspectionId);
    }

    public function update(int $inspectionId, array $inspectionData): void {
        Inspection::query()
            ->where('id', $inspectionId)
            ->update($inspectionData);
    }
}