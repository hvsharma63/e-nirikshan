<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Queries\UserQueries;
use App\Queries\UserQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{

    public function __construct(
        private UserQueries $userQueries,
    ) {
    }
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pf_no' => 'required',
        ]);

        $data = $this->userQueries->getEmailByPF($request->get('pf_no'))->toArray();

        Password::sendResetLink(
            $data
        );

        return back()->with('status', __('A reset link will be sent if the account exists.'));
    }
}
