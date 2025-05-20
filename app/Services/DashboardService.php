<?php

declare(strict_types=1);

namespace App\Services;

use App\Queries\CommentQueries;
use App\Queries\DeficiencyQueries;
use App\Queries\InspectionQueries;
use Carbon\Carbon;

class DashboardService
{
    public function __construct(
        private DeficiencyQueries $deficiencyQueries,
        private InspectionQueries $inspectionQueries,
        private CommentQueries $commentQueries
    ) {
    }

    public function stats(string $timeRange): array
    {

        $fromDate = null;
        $toDate = null;

        switch ($timeRange) {
            case 'today':
                $fromDate = Carbon::today();
                $toDate = Carbon::today()->endOfDay();
                break;

            case 'this_week':
                $fromDate = Carbon::now()->startOfWeek(); // default Monday
                $toDate = Carbon::now()->endOfWeek();
                break;

            case 'this_month':
                $fromDate = Carbon::now()->startOfMonth();
                $toDate = Carbon::now()->endOfMonth();
                break;

            case 'this_financial_year':
                $now = Carbon::now();
                $fromDate = $now->month < 4
                    ? Carbon::create($now->year - 1, 4, 1)
                    : Carbon::create($now->year, 4, 1);
                $toDate = $fromDate->copy()->addYear()->subDay();
                break;

            case 'overall':
            default:
                // No date range filter
                break;
        }

        // Pass date range to query classes if applicable
        if ($fromDate && $toDate) {
            $this->inspectionQueries->setDateRange($fromDate, $toDate);
            $this->deficiencyQueries->setDateRange($fromDate, $toDate);
        }

        return [
            'inspections_total_count' => $this->inspectionQueries->getInspectionsCount(),
            'deficiencies_total_count' => $this->deficiencyQueries->getDeficienciesCount(),
            'deficiencies_pending_count' => $this->deficiencyQueries->getPendingDeficienciesCount(),
            'deficiencies_attended_count' => $this->deficiencyQueries->getAttendedDeficienciesCount(),
        ];
    }
}
