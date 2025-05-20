<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DeficiencyStatusEnum;
use App\Enums\InspectionStatusEnum;
use App\Models\Deficiency;
use App\Queries\CommentQueries;
use App\Queries\DeficiencyQueries;
use App\Queries\InspectionQueries;
use Carbon\Carbon;

class DeficiencyService
{
    public function __construct(
        private DeficiencyQueries $deficiencyQueries,
        private InspectionQueries $inspectionQueries,
        private CommentQueries $commentQueries
    ) {
    }

    public function list(int $userId)
    {
        $deficiency = $this->deficiencyQueries->list($userId);

    }

    public function view(int $deficiencyId, int $userId): ?Deficiency
    {

        $deficiency = $this->deficiencyQueries->view($deficiencyId, $userId);

        if ($deficiency->status != DeficiencyStatusEnum::ATTENDED() && $deficiency->status != DeficiencyStatusEnum::SEEN()) {
            $this->deficiencyQueries->updateStatus($deficiency->id, DeficiencyStatusEnum::SEEN);
        }

        return $deficiency;
    }


    public function attend(int $deficiencyId, int $pertainsToUserId, array $data): void
    {

        $this->deficiencyQueries->updateStatusAndActionDate(
            $deficiencyId,
            DeficiencyStatusEnum::ATTENDED(),
            Carbon::parse($data['action_date'])
        );

        $this->commentQueries->delete($deficiencyId, $pertainsToUserId);

        $this->commentQueries->create($deficiencyId, $pertainsToUserId, $data['comment']);

        $deficiencies = $this->deficiencyQueries->getWhere($data['inspection_id']);

        $attendedDeficiencies = $deficiencies->where('status', DeficiencyStatusEnum::ATTENDED());

        if (count($attendedDeficiencies) === count($deficiencies)) {

            $this->inspectionQueries->update(
                $data['inspection_id'],
                [
                    'status' => InspectionStatusEnum::COMPLETED
                ]
            );

        }
    }
}
