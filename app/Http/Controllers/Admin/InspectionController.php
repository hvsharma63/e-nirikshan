<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Inspection\ListResource;
use App\Http\Resources\Common\ListUserDropdownResource;
use App\Http\Resources\Common\ViewInspectionResource;
use App\Models\Inspection;
use App\Queries\InspectionQueries;
use App\Queries\UserQueries;
use App\Services\InspectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Response as HttpResponse;

class InspectionController extends Controller
{

    public function  __construct(
        private UserQueries $userQueries,
        private InspectionQueries $inspectionQueries,
        private InspectionService $inspectionService,
    ) {
    }

    public function index(): Response
    {
        $users = $this->userQueries->getUsers();

        return Inertia::render('admin/inspections/List', [
            'users' => ListUserDropdownResource::collection($users),
        ]);
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        if ($request->query('from') && $request->query('to')) {
            $from = Carbon::parse($request->query('from'));
            $to = Carbon::parse($request->query('to'));
            $this->inspectionQueries->setDateRange($from, $to);
        }
        
        $search = $request->query('search') ?? null;
        $sortOrder = $request->query('sort_order') ?? null;
        $sortBy = $request->query('sort_by') ?? null;
        $users = $request->query('users') ?? null;
        $inspections = $this->inspectionQueries->listAll($search, $sortBy, $sortOrder, $users);

        return ListResource::collection($inspections);
    }

    public function view(int $id): Response
    {
        $inspection = $this->inspectionQueries->get($id);
        
        return Inertia::render('admin/inspections/View', [
            'inspection' => new ViewInspectionResource($inspection),
        ]);
    }

    
    public function viewNote(int $userId, int $inspectionId): HttpResponse
    {
        $inspection = $this->inspectionService->getNoteByInspectingOfficer($inspectionId,$userId);

        return Pdf::loadView('pdfs.note', ['inspection' => $inspection])
            ->stream('inspection-note.pdf');
    }

    public function downloadNote(int $userId, int $inspectionId): HttpResponse
    {
        $inspection = $this->inspectionService->getNoteByInspectingOfficer($inspectionId,$userId);

        $fileName = 'inspection-note-' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return Pdf::loadView('pdfs.note', ['inspection' => $inspection])
            ->download($fileName);
    }
}
