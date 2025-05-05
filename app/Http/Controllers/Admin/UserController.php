<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\ListResource;
use App\Http\Resources\Admin\User\ViewResource;
use App\Queries\InspectionQueries;
use App\Queries\UserQueries;
use App\Services\InspectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{

    public function  __construct(
        private UserQueries $userQueries,
        private InspectionQueries $inspectionQueries,
        private InspectionService $inspectionService,
    ) {
    }

    public function index(): Response
    {
        return Inertia::render('admin/users/List');
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        $search = $request->query('search') ?? null;
        $users = $this->userQueries->getAllUsers($search);

        return ListResource::collection($users);
    }


    public function details(int $userId): Response
    {
        $user = $this->userQueries->getUserById($userId);

        return Inertia::render('admin/users/View', [
            'user' => new ViewResource($user),
        ]);
    }
}
