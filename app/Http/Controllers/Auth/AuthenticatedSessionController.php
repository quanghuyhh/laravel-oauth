<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        public SocialAccountService $socialAccountService,
    )
    {
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('users.auth.login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->socialAccountService->logout($request);
        return redirect()->guest(route('home'));
    }
}
