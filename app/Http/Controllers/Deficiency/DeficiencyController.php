<?php

namespace App\Http\Controllers\Deficiency;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DeficiencyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('deficiencies/List');
    }

    public function view(): Response
    {
        return Inertia::render('deficiencies/View');
    }
}
