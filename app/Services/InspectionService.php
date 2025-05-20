<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InspectionStatusEnum;
use App\Jobs\SendDeficiencyNotificationJob;
use App\Models\Inspection;
use App\Queries\InspectionQueries;
use App\Queries\TemporaryUploadQueries;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InspectionService
{
    public function __construct(
        private InspectionQueries $inspectionQueries,
        private TemporaryUploadQueries $temporaryUploadQueries
    ) {
    }

    public function createInspection(array $inspectionData)
    {
        $deficiencies = $inspectionData["deficiencies"];
        $yetToBeCreatedDeficiencies = $inspectionData["deficiencies"];

        if ($inspectionData['no_deficiencies_found']) {
            $inspectionData['status'] = InspectionStatusEnum::COMPLETED();
        }

        $inspection = $this->inspectionQueries->create(
            Arr::except($inspectionData, [
                'deficiencies',
                'temporary_upload_uuid',
                'is_draft',
            ])
        );

        if (!empty($deficiencies)) {
            $deficiencies = $this->inspectionQueries->createManyDeficiencies(
                $inspection,
                Arr::except($deficiencies, 'temporary_upload_uuid')
            );

            foreach ($deficiencies as $index => $deficiency) {

                if (!empty($yetToBeCreatedDeficiencies[$index]['temporary_upload_uuid'])) {
                    $temporaryUploadRecord = $this->temporaryUploadQueries->getTemporaryUploads($yetToBeCreatedDeficiencies[$index]['temporary_upload_uuid'], Auth::id());

                    if ($temporaryUploadRecord) {
                        foreach ($temporaryUploadRecord->getMedia('temporary_uploads') as $media) {
                            $newFileName = uniqid('deficiency_', true) . '-' . Str::uuid() . '.' . $media->extension;
                            $media->copy($deficiency, 'deficiency_photos', 'private', $newFileName);
                        }
                        $temporaryUploadRecord->delete();
                    }
                }
                dispatch(new SendDeficiencyNotificationJob($deficiency))->afterCommit();
            }
        }

        return $inspection;
    }

    public function getNoteByInspectingOfficer(int $inspectionId, int $userId): ?Inspection
    {

        return $this->inspectionQueries->viewNotePdfByInspectingOfficer($inspectionId, $userId);
    }
}
