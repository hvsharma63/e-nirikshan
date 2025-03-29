<?php

namespace App\Http\Controllers\Deficiency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Deficiency\ListDeficiencyResource;
use App\Queries\DeficiencyQueries;
use Illuminate\Support\Facades\Auth;
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
}
