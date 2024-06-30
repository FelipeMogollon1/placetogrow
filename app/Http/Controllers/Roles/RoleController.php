<?php

namespace App\Http\Controllers\Roles;

use App\Domain\Role\Actions\StoreRoleAction;
use App\Domain\Role\Actions\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('Roles/Index',['roles' => Role::all()]);
    }

    public function create(): Response
    {
        $permissions =  Permission::all();
        return Inertia::render('Roles/Create',compact('permissions'));
    }

    public function store(StoreRoleRequest $request, StoreRoleAction $storeAction): RedirectResponse
    {
        return $storeAction->execute($request->validated());
    }

    public function edit(string $id): Response
    {   $roles = Role::find($id);
        return Inertia::render('Roles/Edit', compact('roles'));
    }

    public function update(UpdateRoleRequest $request, string $id,UpdateRoleAction $updateAction): RedirectResponse
    {
        return $updateAction->execute($id, $request->validated());
    }


    public function destroy(Role $id): RedirectResponse
    {
        $id->delete();
        return redirect()->route('roles.index');
    }
}
