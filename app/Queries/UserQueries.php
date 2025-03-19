<?php

namespace App\Queries;

use App\Models\User;

class UserQueries {

    public function __construct() {
    }

    public function getEmailByPF($pfNo): User|null {  
        return User::query()
            ->where('pf_no', $pfNo)
            ->first('email'); 
    }
}
?>