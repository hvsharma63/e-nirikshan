<?php

declare(strict_types=1);

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inspection\InspectionCreateRequest;
use App\Http\Resources\Officer\Inspection\ListResource;
use App\Http\Resources\Common\ViewInspectionResource;
use App\Queries\InspectionQueries;
use App\Queries\UserQueries;
use App\Services\InspectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class InspectionController extends Controller
{
    public function __construct(
        private InspectionService $inspectionService,
        private InspectionQueries $inspectionQueries,
        private UserQueries $userQueries,
    ) {
    }

    public function create(): Response
    {
        return Inertia::render('officer/inspections/Create');
    }

    public function save(InspectionCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $this->inspectionService->createInspection($validatedData);
            DB::commit();
            $action = $validatedData['is_draft'] ? 'saved' : 'created';
            return Redirect::route('officer.inspections.index')->with('success', "Inspection $action successfully");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function index(): Response
    {
        return Inertia::render('officer/inspections/List');
    }

    public function view(int $id): Response
    {
        $inspection = $this->inspectionQueries->get($id, Auth::id());

        return Inertia::render('officer/inspections/View', [
            'inspection' => new ViewInspectionResource($inspection),
        ]);
    }

    public function list(Request $request): AnonymousResourceCollection
    {

        $inspections = $this->inspectionQueries->list(Auth::id());

        return ListResource::collection($inspections);
    }

    public function viewNote(int $id): HttpResponse
    {
        $inspection = $this->inspectionService->getNoteByInspectingOfficer($id, Auth::id());

        return Pdf::loadView('pdfs.note', ['inspection' => $inspection])
            ->stream('inspection-note.pdf');
    }

    public function downloadNote(int $id): HttpResponse
    {
        $inspection = $this->inspectionService->getNoteByInspectingOfficer($id, Auth::id());

        $fileName = 'inspection-note-' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return Pdf::loadView('pdfs.note', ['inspection' => $inspection])
            ->download($fileName);
    }
}
