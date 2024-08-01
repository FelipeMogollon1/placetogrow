<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Abilities;
use App\Domain\User\Actions\StoreUserAction;
use App\Domain\User\Actions\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
    public function index(): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, User::class);
        $users = User::with('roles')->get();

        return Inertia::render('Users/Index', compact('users'));
    }

    public function create(): Response
    {
        $this->authorize(Abilities::CREATE->value, User::class);

        return Inertia::render('Users/Create', ['roles' => Role::select('name')->get()]);
    }

    public function store(StoreUserRequest $request, StoreUserAction $storeAction): RedirectResponse
    {
        $this->authorize(Abilities::STORE->value, User::class);

        return $storeAction->execute($request->validated());
    }

    public function edit(string $id): Response
    {
        $this->authorize(Abilities::EDIT->value, User::class);
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return Inertia::render('Users/Edit', compact('user','roles'));
    }

    public function update(UpdateUserRequest $request, string $id, UpdateUserAction $updateAction): RedirectResponse
    {
        $this->authorize(Abilities::UPDATE->value, User::class);

        return $updateAction->execute($id, $request->validated());
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize(Abilities::DELETE->value, User::class);
        $user->delete();

        return redirect()->route('users.index');
    }
}
