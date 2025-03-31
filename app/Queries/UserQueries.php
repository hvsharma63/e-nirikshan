<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Support\Collection;

class UserQueries {

    public function __construct() {
    }

    public function getEmailByPF($pfNo): User|null {  
        return User::query()
            ->where('pf_no', $pfNo)
            ->first('email'); 
    }

    public function getUsers($loggedInUserId): Collection {  
        return User::query()
            ->where('id', '!=', $loggedInUserId)
            ->select(['id', 'name', 'designation'])
            ->get();   
    }
}
?>