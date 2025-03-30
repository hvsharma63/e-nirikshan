<?php

namespace App\Http\Controllers\Deficiency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Deficiency\ListDeficiencyResource;
use App\Notifications\DeficiencyNotification;
use App\Queries\DeficiencyQueries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DeficiencyController extends Controller
{
    public function __construct(
        private DeficiencyQueries $deficiencyQueries
    ) {
    }
    
    public function index(): Response
    {
        $deficiencies = $this->deficiencyQueries->list(Auth::id());

        return Inertia::render('deficiencies/List',[
            'deficiencies' => ListDeficiencyResource::collection($deficiencies),
        ]);
    }

    public function view(): Response
    {
        return Inertia::render('deficiencies/View');
    }

    public function remind(int $id): RedirectResponse {
        $deficiency = $this->deficiencyQueries->get($id);
        $deficiency->pertainsTo->notify(new DeficiencyNotification($deficiency));
        return Redirect::route('inspections.view', ['id' => $id])
            ->with('success', "Reminder Sent Successfully");

    }
}
