<?php

namespace App\Http\Controllers\Auth;

use App\Constants\DocumentTypes;
use App\Constants\Roles;
use App\Domain\Auth\Action\StoreRegisteredUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        $roleGuest = Roles::GUEST->value;
        $documentTypes = DocumentTypes::getDocumentTypes();

        return Inertia::render('Auth/Register', compact('documentTypes', 'roleGuest'));
    }


    public function store(StoreUserRequest $request, StoreRegisteredUserAction $action): RedirectResponse
    {
        $action->execute($request->validated());
        $rolesUser = auth()->user()->roles->pluck('name')->toArray();

        if (in_array(Roles::GUEST->value, $rolesUser)) {
            return redirect()->intended(route('profile.edit', absolute: false));
        }
        return redirect(route('dashboard.index', absolute: false));
    }

}
