<?php

namespace App\Http\Controllers\Deficiency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deficiency\AttendDeficiencyRequest;
use App\Http\Resources\Deficiency\ListDeficiencyResource;
use App\Http\Resources\ViewDeficiencyResource;
use App\Jobs\SendDeficiencyNotificationJob;
use App\Queries\DeficiencyQueries;
use App\Services\DeficiencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DeficiencyController extends Controller
{
    public function __construct(
        private DeficiencyQueries $deficiencyQueries,
        private DeficiencyService $deficiencyService
    ) {
    }
    
    public function index(): Response
    {
        return Inertia::render('deficiencies/List');
    }

    public function view(int $id): Response
    {
        $deficiency = $this->deficiencyService->view($id, Auth::id());

        return Inertia::render('deficiencies/View',[
            'deficiency' => new ViewDeficiencyResource($deficiency)
        ]);
    }

    public function remind(int $id): RedirectResponse {

        $deficiency = $this->deficiencyQueries->get($id);
        
        dispatch(new SendDeficiencyNotificationJob($deficiency));
        
        return Redirect::route('inspections.view', ['id' => $id])
            ->with('success', "Reminder Sent Successfully");
    }

    public function attend(int $id, AttendDeficiencyRequest $request): RedirectResponse {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            $this->deficiencyService->attend($id, Auth::id(), $data);
            
            DB::commit();
            
            return Redirect::route('deficiencies.view', ['id' => $id])
                ->with('success', "Deficiency Attended Successfully");
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function list(Request $request): AnonymousResourceCollection {
        $deficiencies = $this->deficiencyQueries->list(Auth::id());
        return ListDeficiencyResource::collection($deficiencies);
    }
}
