<?php

namespace App\Http\Controllers\Inspection;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inspection\InspectionCreateRequest;
use App\Http\Resources\Inspection\ListInspectionResource;
use App\Queries\InspectionQueries;
use App\Services\InspectionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class InspectionController extends Controller
{

    public function  __construct(
        private InspectionService $inspectionService,
        private InspectionQueries $inspectionQueries
    ) {
    }

    public function create(): Response
    {
        return Inertia::render('inspection/Create');
    }

    public function save(InspectionCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $this->inspectionService->createInspection($validatedData);
            DB::commit();
            $action = $validatedData['is_draft'] ? 'saved' : 'created';
            return Redirect::route('inspections.index')->with('success', "Inspection $action successfully");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function index(): Response
    {
        $inspections = $this->inspectionQueries->list(Auth::id());
        return Inertia::render('inspection/List', [
            'inspections' => ListInspectionResource::collection($inspections),
        ]);
    }

    public function view(): Response
    {
        return Inertia::render('inspection/View');
    }
}
