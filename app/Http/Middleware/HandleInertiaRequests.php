<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Generate a random inspiring quote
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        // Fetch the currently authenticated user
        $loggedInUser = $request->user()
            ? $request->user()->load([
                'activeDesignation:user_id,address_asc',
            ])
            : null;

        // Retrieve the roles and permissions, ensuring we handle null values properly
        $roles = $loggedInUser ? $loggedInUser->getRoleNames() : [];
        $permissions = $loggedInUser ? $loggedInUser->getAllPermissions()->pluck('name') : [];

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $loggedInUser,
                'roles' => $roles,
                'permissions' => $permissions,
            ],
            'ziggy' => [
                ...(new Ziggy())->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ];
    }
}
