<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InspectionStatusEnum;
use App\Jobs\SendDeficiencyNotificationJob;
use App\Models\Deficiency;
use App\Models\Inspection;
use App\Queries\InspectionQueries;
use App\Queries\TemporaryUploadQueries;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InspectionService
{
    public function __construct(
        private InspectionQueries $inspectionQueries,
        private TemporaryUploadQueries $temporaryUploadQueries
    ) {
    }

    public function createInspection(array $inspectionData): Inspection
    {
        /** @var array $deficiencies */
        $deficiencies = $inspectionData["deficiencies"];
        $yetToBeCreatedDeficiencies = $inspectionData["deficiencies"];

        if ($inspectionData['no_deficiencies_found']) {
            $inspectionData['status'] = InspectionStatusEnum::COMPLETED();
        }

        $inspection = $this->inspectionQueries->create(
            Arr::except($inspectionData, [
                'deficiencies',
                'is_draft',
            ])
        );

        if (!empty($deficiencies)) {
            
            $deficienciesToBeCreated = Arr::map($deficiencies, function ($item) {
                return Arr::except($item, ['temporary_upload_uuid']);
            });

            /** @var Collection<int, Deficiency> $deficiencies */
            $deficiencies = $this->inspectionQueries->createManyDeficiencies(
                $inspection,
    $deficienciesToBeCreated
            );

            foreach ($deficiencies as $index => $deficiency) {
                /** @var Deficiency $deficiency */
                
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
