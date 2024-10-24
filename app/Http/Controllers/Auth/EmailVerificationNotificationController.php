<?php

namespace App\Http\Controllers\Auth;

use App\Constants\Roles;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {

            $rolesUser = auth()->user()->roles->pluck('name')->toArray();

            if (in_array(Roles::GUEST->value, $rolesUser)) {
                return redirect()->intended(route('profile.edit', absolute: false));
            }

            return redirect()->intended(route('dashboard.index', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
