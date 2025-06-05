<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\DeficiencyStatusEnum;
use App\Models\Deficiency;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DeficiencyQueries
{
    public $fromDate = null;
    public $toDate = null;

    public function __construct()
    {
    }

    public function setDateRange(Carbon $from, Carbon $to)
    {
        $this->fromDate = $from;
        $this->toDate = $to;
    }

    public function list(int $userId): LengthAwarePaginator
    {
        return Deficiency::query()
            ->withOnly(['inspection:id,location,attended_by,datetime','inspection.attendedBy:id,name'])
            ->select(['id', 'inspection_id', 'action_date', 'status'])
            ->where('pertains_to', $userId)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function get(int $deficiencyId): ?Deficiency
    {
        return Deficiency::query()
            ->withOnly(['pertainsTo:id,name,email'])
            ->findOrFail($deficiencyId);
    }

    public function view(int $deficiencyId, int $userId): ?Deficiency
    {
        return Deficiency::query()
            ->withOnly([
                'inspection:id,location,attended_by,datetime,day_period,status',
                'inspection.attendedBy:id,name',
                'inspection.attendedBy.activeDesignation:id,user_id,designation_id,address_asc',
                'comment:id,deficiency_id,comment_by,comment',
                'media',
            ])
            ->select(['id', 'inspection_id','pertains_to', 'note', 'status','action_date', 'created_at'])
            ->where('pertains_to', $userId)
            ->findOrFail($deficiencyId);
    }

    public function viewForAdmin(int $deficiencyId, ?int $userId = null): ?Deficiency
    {
        return Deficiency::query()
            ->withOnly([
                'inspection:id,location,attended_by,datetime,day_period,status',
                'inspection.attendedBy:id,name',
                'inspection.attendedBy.activeDesignation:id,user_id,designation_id,address_asc',
                'comment:id,deficiency_id,comment_by,comment',
                'pertainsTo.activeDesignation:id,user_id,address_asc',
                'media',
            ])
            ->select(['id', 'inspection_id','pertains_to', 'note', 'status','action_date', 'created_at'])
            ->when($userId, function ($query) use ($userId) {
                $query->where('pertains_to', $userId);
            })
            ->findOrFail($deficiencyId);
    }

    public function updateStatus(int $deficiencyId, int $status): void
    {
        Deficiency::query()
            ->where('id', $deficiencyId)
            ->update([
                'status' => $status
            ]);
    }

    public function updateStatusAndActionDate(int $deficiencyId, DeficiencyStatusEnum $status, Carbon $actionDate): void
    {
        Deficiency::query()
            ->where('id', $deficiencyId)
            ->update([
                'action_date' => $actionDate,
                'status' => $status
            ]);
    }

    public function getWhere(int $inspectionId): Collection
    {
        return Deficiency::query()
            ->select(['id', 'inspection_id', 'status'])
            ->where('inspection_id', $inspectionId)
            ->get();
    }

    public function getPendingDeficiencies(): Collection
    {
        return Deficiency::query()
            ->withOnly(['inspection:id,location', 'pertainsTo:id,name,email'])
            ->select(['id', 'inspection_id','pertains_to','note','status', 'created_at'])
            ->where('status', DeficiencyStatusEnum::PENDING)
            ->get();
    }

    public function getDeficienciesCount(): int
    {
        return Deficiency::query()
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
            })
            ->count();
    }

    public function getPendingDeficienciesCount(): int
    {
        return Deficiency::query()
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
            })
            ->where('status', DeficiencyStatusEnum::PENDING)
            ->count();
    }

    public function getAttendedDeficienciesCount(): int
    {
        return Deficiency::query()
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
            })
            ->where('status', DeficiencyStatusEnum::ATTENDED)
            ->count();
    }

    public function listAll(
        ?string $search,
        ?string $inspectorId,
        ?string $pertainingOfficerId
    ): LengthAwarePaginator {
        return Deficiency::query()
            ->withOnly([
                'inspection:id,location,attended_by,datetime',
                'inspection.attendedBy:id,name',
                'inspection.attendedBy.activeDesignation:id,user_id,address_asc',
                'pertainsTo:id,name',
                'pertainsTo.activeDesignation:id,user_id,address_asc'
            ])
            ->select(['id', 'inspection_id', 'note', 'pertains_to', 'action_date', 'status'])
            ->when($search, function ($query) use ($search) {
                $query->where('note', 'like', "%{$search}%")
                    ->orWhereHas('inspection', function ($q) use ($search) {
                        $q->where('location', 'like', "%{$search}%");
                    });
            })
            ->when($inspectorId, function ($query) use ($inspectorId) {
                $query->whereHas('inspection.attendedBy', function ($q) use ($inspectorId) {
                    $q->where('id', $inspectorId);
                });
            })
            ->when($pertainingOfficerId, function ($query) use ($pertainingOfficerId) {
                $query->where('pertains_to', $pertainingOfficerId);
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereHas('inspection', function ($q) {
                    $q->whereBetween('datetime', [$this->fromDate, $this->toDate]);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}
