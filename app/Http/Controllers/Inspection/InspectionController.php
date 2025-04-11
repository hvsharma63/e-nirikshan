<?php

namespace App\Http\Controllers\Inspection;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inspection\InspectionCreateRequest;
use App\Http\Resources\Inspection\ListInspectionResource;
use App\Http\Resources\ViewInspectionResource;
use App\Models\Inspection;
use App\Queries\InspectionQueries;
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
        return Inertia::render('inspection/List');
    }

    public function view(int $id): Response
    {
        $inspection = $this->inspectionQueries->get($id, Auth::id());

        return Inertia::render('inspection/View', [
            'inspection' => new ViewInspectionResource($inspection),
        ]);
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        
        $inspections = $this->inspectionQueries->list(Auth::id());

        return ListInspectionResource::collection($inspections);
    }

    private function getNote(int $id): ?Inspection {
        return $this->inspectionQueries->viewNotePdfByInspectingOfficer($id, Auth::id());
    }
    
    public function viewNote(int $id): HttpResponse
    {
        $inspection = $this->getNote($id);

        return Pdf::loadView('pdfs.note', ['inspection' => $inspection])
            ->stream('inspection-note.pdf');
    }

    public function downloadNote(int $id): HttpResponse
    {
        $inspection = $this->getNote($id);

        $fileName = 'inspection-note-' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return Pdf::loadView('pdfs.note', ['inspection' => $inspection])
            ->download($fileName);
    }
}
