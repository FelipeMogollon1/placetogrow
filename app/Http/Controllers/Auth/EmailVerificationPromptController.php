<?php

namespace App\Http\Controllers\Auth;

use App\Constants\Roles;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $rolesUser = auth()->user()->roles->pluck('name')->toArray();

        if (in_array(Roles::GUEST->value, $rolesUser)) {

            return $request->user()->hasVerifiedEmail()
                ? redirect()->intended(route('profile.edit', absolute: false))
                : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard.index', absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
