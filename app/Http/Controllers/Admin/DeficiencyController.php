<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Deficiency\ListResource;
use App\Http\Resources\Admin\Deficiency\ViewResource;
use App\Http\Resources\Common\ListUserDropdownResource;
use App\Queries\DeficiencyQueries;
use App\Queries\InspectionQueries;
use App\Queries\UserQueries;
use App\Services\DeficiencyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class DeficiencyController extends Controller
{
    public function __construct(
        private DeficiencyQueries $deficiencyQueries,
        private InspectionQueries $inspectionQueries,
        private DeficiencyService $deficiencyService,
        private UserQueries $userQueries,
    ) {
    }
    
    public function index(): Response
    {
        $users = $this->userQueries->getUsers();

        return Inertia::render('admin/deficiencies/List', [
            'users' => ListUserDropdownResource::collection($users),
        ]);
    }

    public function list(Request $request): AnonymousResourceCollection {
        
        if ($request->query('from') && $request->query('to')) {
            $from = Carbon::parse($request->query('from'));
            $to = Carbon::parse($request->query('to'));
            $this->deficiencyQueries->setDateRange($from, $to);
        }
        
        $search = $request->query('search') ?? null;
        $inspector = $request->query('inspector') ?? null;
        $pertaining_officer = $request->query('pertaining_officer') ?? null;

        $deficiencies = $this->deficiencyQueries->listAll(
            $search,
            $inspector,
            $pertaining_officer
        );
        
        return ListResource::collection($deficiencies);
    }

    public function view(int $id): Response
    {
        $deficiency = $this->deficiencyQueries->viewForAdmin($id);

        return Inertia::render('admin/deficiencies/View',[
            'deficiency' => new ViewResource($deficiency)
        ]);
    }
}
