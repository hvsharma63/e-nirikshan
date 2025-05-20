<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User;
use App\Enums\DesignationLevelEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserQueries
{
    public function __construct()
    {
    }

    public function getEmailByPF($pfNo): User|null
    {
        return User::query()
            ->where('pf_no', $pfNo)
            ->first('email');
    }

    public function getBranchOfficers(?int $loggedInUserId): Collection
    {
        return User::query()
            ->with(['activeDesignation:id,user_id,designation_id,address_asc'])
            ->whereHas('activeDesignation.designation', function ($query) {
                return $query->where('level', DesignationLevelEnum::BRANCH_OFFICER);
            })
            ->when($loggedInUserId, function ($query) use ($loggedInUserId) {
                return $query->where('id', '!=', $loggedInUserId);
            })
            ->select(['id', 'name'])
            ->get();
    }

    public function getUsers(): Collection
    {
        return User::query()
            ->with(['activeDesignation:id,user_id,designation_id,address_asc'])
            ->select(['id', 'name'])
            ->get();
    }

    public function getAllUsers(string $search = null): LengthAwarePaginator
    {
        return User::query()
            ->select(['id', 'name', 'pf_no', 'mobile_no'])
            ->with([
                'activeDesignation:id,user_id,designation_id,address_asc',
            ])
            ->withCount([
                'inspectionsAttended',
                'deficienciesAttended',
            ])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('pf_no', 'like', "%{$search}%")
                        ->orWhere('mobile_no', 'like', "%{$search}%")
                        ->orWhereHas('activeDesignation', function ($query) use ($search) {
                            return $query->where('address_asc', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getUserById(int $userId): ?User
    {
        return User::query()
            ->select(['id', 'name', 'pf_no', 'email', 'mobile_no', 'dob'])
            ->with([
                'activeDesignation:id,user_id,designation_id,address_asc',
                'recentInspections:id,datetime,location,attended_by,status',
                'recentDeficiencies:id,note,pertains_to,created_at,status',
            ])
            ->withCount([
                'inspectionsInProgress',
                'inspectionsCompleted',
                'inspectionsAttended',
                'deficienciesPending',
                'deficienciesSeen',
                'deficienciesAttended',
                'deficienciesTotal',
            ])
            ->findOrFail($userId);
    }
}
