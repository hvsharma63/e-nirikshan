<?php

namespace App\Services;

use App\Jobs\SendDeficiencyNotificationJob;
use App\Queries\DeficiencyQueries;
use App\Queries\InspectionQueries;

class DeficiencyService {

    public function __construct(
        private DeficiencyQueries $deficiencyQueries
    ) {
    }

    public function list(int $userId) {
        $deficiency = $this->deficiencyQueries->list($userId);
        
    }

    public function get(int $id) {
        
    }
}