<?php

namespace App\Http\Controllers\Api;

use App\Domain\User\Actions\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Domain\User\Actions\StoreUserAction;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    use AuthorizesRequests;
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return Inertia::render('Users/Index', compact('users'));
    }

    public function create(): Response
    {
        $this->authorize('create', User::class);
        return Inertia::render('Users/Create', ['roles' => Role::select('name')->get()]);
    }

    public function store(StoreUserRequest $request, StoreUserAction $storeAction): RedirectResponse
    {
        $this->authorize('store', User::class);
        return $storeAction->execute($request->validated());
    }

    public function edit(string $id): Response
    {
        $this->authorize('edit', User::class);
        $user = User::find($id);
        return Inertia::render('Users/Edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, string $id, UpdateUserAction $updateAction): RedirectResponse
    {
        $this->authorize('update', User::class);
        return $updateAction->execute($id, $request->validated());
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', User::class);
        $user->delete();
        return redirect()->route('users.index');
    }
}
