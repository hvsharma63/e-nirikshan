<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
    ) {
    }


    public function index()
    {
        if(Auth::user()->hasRole(RoleEnum::ADMIN)) {
            return Inertia::render('admin/Dashboard');
        }
        
        return Inertia::render('officer/Dashboard');
    }

    public function stats(Request $request) {
        $timeRange = $request->input('time_range');

        $stats = $this->dashboardService->stats($timeRange);

        return response()->json($stats);
    }
}
