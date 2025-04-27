<?php

namespace App\Services;

use App\Enums\InspectionStatusEnum;
use App\Jobs\SendDeficiencyNotificationJob;
use App\Models\Inspection;
use App\Queries\InspectionQueries;

class InspectionService {

    public function __construct(
        private InspectionQueries $inspectionQueries
    ) {
    }

    public function createInspection(array $inspectionData) {
        $deficiencies = $inspectionData["deficiencies"];

        unset($inspectionData["is_draft"], $inspectionData["deficiencies"]);

        if($inspectionData['no_deficiencies_found']) {
            $inspectionData['status'] = InspectionStatusEnum::COMPLETED();
        }

        $inspection = $this->inspectionQueries->create($inspectionData);

        if (!empty($deficiencies)) {
            $deficiencies = $inspection->deficiencies()->createMany($deficiencies);
            
            foreach ($deficiencies as $deficiency) {
                dispatch(new SendDeficiencyNotificationJob($deficiency));
            }            
        }

        return $inspection;
    }

    public function getNoteByInspectingOfficer(int $inspectionId, int $userId): ?Inspection {

        return $this->inspectionQueries->viewNotePdfByInspectingOfficer($inspectionId, $userId);
    }
}