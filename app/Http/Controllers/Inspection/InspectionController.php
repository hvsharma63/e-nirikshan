<?php

namespace App\Http\Controllers\Inspection;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InspectionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('inspection/Create');
    }

    public function index(): Response
    {
        return Inertia::render('inspection/List');
    }

    public function view(): Response
    {
        return Inertia::render('inspection/View');
    }
}
