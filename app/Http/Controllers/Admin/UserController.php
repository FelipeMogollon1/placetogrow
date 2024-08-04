<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Abilities;
use App\Domain\User\Actions\DestroyUserAction;
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
        $users = User::with('roles')->paginate(5);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create(): Response
    {
        $this->authorize(Abilities::CREATE->value, User::class);

        return Inertia::render('Users/Create', ['roles' => Role::select('name')->get()]);
    }

    public function store(StoreUserRequest $request, StoreUserAction $storeAction): RedirectResponse
    {
        $this->authorize(Abilities::STORE->value, User::class);
        $storeAction->execute($request->validated());

        return to_route('users.index');
    }

    public function edit(string $id): Response
    {
        $this->authorize(Abilities::EDIT->value, User::class);
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return Inertia::render('Users/Edit', compact('user','roles'));
    }

    public function update(UpdateUserRequest $request,User $user, UpdateUserAction $updateAction): RedirectResponse
    {
        $this->authorize(Abilities::UPDATE->value, User::class);
        $updateAction->execute($user->id, $request->validated());

        return to_route('users.index');
    }

    public function destroy(User $user, DestroyUserAction $action): RedirectResponse
    {
        $this->authorize(Abilities::DELETE->value, User::class);

        if ($action->execute($user)) {
            return redirect()
                ->route('users.index')
                ->with('error', 'The user cannot be deleted because they have microsites assigned.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User successfully deleted.');
    }
}
