<?php

namespace App\Http\Controllers\Api;

use App\Domain\User\Actions\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Domain\User\Actions\StoreUserAction;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class userController extends Controller
{

    public function index(): Response
    {
        $users = user::all();
        return Inertia::render('Users/Index', compact('users'));
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create', ['roles' => Role::select('name')->get()]);
    }

     public function store(StoreUserRequest $request, StoreUserAction $storeAction): RedirectResponse
     {
       return $storeAction->execute($request->validated());
     }

     public function show(string $id): Response
     {
        $user = User::find($id);
        return Inertia::render('Users/Show',compact('user'));

    }

    public function edit(string $id): Response
    {
        $user = User::find($id);
        return Inertia::render('Users/Edit',compact('user'));
    }

    public function update(UpdateUserRequest $request, string $id, UpdateUserAction $updateAction): RedirectResponse
    {
        return $updateAction->execute($id, $request->validated());
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
