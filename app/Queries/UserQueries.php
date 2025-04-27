<?php

namespace App\Queries;

use App\Models\User;
use App\Enums\DesignationLevelEnum;
use Illuminate\Support\Collection;

class UserQueries {

    public function __construct() {
    }

    public function getEmailByPF($pfNo): User|null {  
        return User::query()
            ->where('pf_no', $pfNo)
            ->first('email'); 
    }

    public function getBranchOfficers(?int $loggedInUserId): Collection {  
        return User::query()
            ->with(['activeDesignation:id,user_id,designation_id,address_asc'])
            ->whereHas('activeDesignation.designation', function($query) {
                return $query->where('level', DesignationLevelEnum::BRANCH_OFFICER);
            })
            ->when($loggedInUserId, function($query) use ($loggedInUserId) {
                return $query->where('id', '!=', $loggedInUserId);
            })
            ->select(['id', 'name'])
            ->get();   
    }

    public function getUsers(): Collection {  
        return User::query()
            ->with(['activeDesignation:id,user_id,designation_id,address_asc'])
            ->select(['id', 'name'])
            ->get();   
    }
}
?>