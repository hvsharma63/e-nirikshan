<?php

namespace App\Queries;

use App\Models\Inspection;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class InspectionQueries {

    public $fromDate = null;
    public $toDate = null;

    public function __construct() {
    }


    public function setDateRange(Carbon $from, Carbon $to)
    {
        $this->fromDate = $from;
        $this->toDate = $to;
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

    public function get(int $inspectionId, ?int $userId= null): ?Inspection {
        return Inspection::query()
            ->select(['id','location', 'datetime', 'attended_by', 'day_period', 'no_deficiencies_found', 'status'])
            ->withOnly([
                'attendedBy:id,name',
                'deficiencies.pertainsTo.activeDesignation:id,user_id,address_asc',
                'deficiencies.comment'
            ])
            ->when($userId, function ($query) use ($userId) {
                $query->where('attended_by', $userId);
            })
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
            ->select(['id', 'location', 'datetime', 'attended_by', 'day_period', 'no_deficiencies_found', 'status'])
            ->whereHas('deficiencies.pertainsTo', function ($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->withOnly([
                'attendedBy:id,name',
                'deficiencies.pertainsTo' => function ($query) use ($userId) {
                    $query->select(['id', 'name'])
                        ->where('id', $userId)
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

    public function getInspectionsCount(): int {
        return Inspection::query()
        ->when($this->fromDate && $this->toDate, function ($query) {
            $query->whereBetween('datetime', [$this->fromDate, $this->toDate]);
        })->count();
    }

    public function listAll(?string $search, ?string $sortBy, ?string $sortOrder, ?array $userIds): LengthAwarePaginator
    {
        $query = Inspection::query()
            ->select(['id', 'location', 'datetime', 'day_period', 'status', 'attended_by'])
            ->with([
                'attendedBy:id,name',
                'attendedBy.activeDesignation:id,user_id,address_asc'
            ])
            ->withCount('deficiencies');

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('datetime', [$this->fromDate, $this->toDate]);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                ->orWhereHas('attendedBy', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if($userIds) {
            $query->whereIn('attended_by', $userIds);
        }

        // Handle multi-sorting
        if ($sortBy && str_contains($sortBy, ',')) {
            $sortColumns = explode(',', $sortBy);
            $sortOrders = explode(',', $sortOrder ?? '');

            foreach ($sortColumns as $index => $column) {
                $order = $sortOrders[$index] ?? 'asc';
                $query->orderBy($column, $order);
            }
        } else {
            $query->orderBy($sortBy ?? 'datetime', $sortOrder ?? 'desc');
        }

        return $query->paginate(10);
    }
}