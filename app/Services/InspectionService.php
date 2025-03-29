<?php

namespace App\Services;

use App\Jobs\SendDeficiencyNotificationJob;
use App\Queries\InspectionQueries;

class InspectionService {

    public function __construct(
        private InspectionQueries $inspectionQueries
    ) {
    }

    public function createInspection(array $inspectionData) {
        $deficiencies = $inspectionData["deficiencies"];

        unset($inspectionData["is_draft"], $inspectionData["deficiencies"]);
        
        $inspection = $this->inspectionQueries->create($inspectionData);

        if (!empty($deficiencies)) {
            $deficiencies = $inspection->deficiencies()->createMany($deficiencies);
            
            foreach ($deficiencies as $deficiency) {
                dispatch(new SendDeficiencyNotificationJob($deficiency));
            }            
        }

        return $inspection;
    }

    public function get(int $id) {
        
    }
}