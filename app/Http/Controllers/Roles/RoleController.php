<?php

namespace App\Http\Controllers\Roles;

use App\Constants\Roles;
use App\Domain\Role\Actions\DestroyRoleAction;
use App\Domain\Role\Actions\StoreRoleAction;
use App\Domain\Role\Actions\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        return Inertia::render('Roles/Index', [
         'roles' => Role::orderBy('name')->paginate(5)
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Roles/Create', ['permissions' => Permission::all()]);
    }

    public function store(StoreRoleRequest $request, StoreRoleAction $storeAction): RedirectResponse
    {
        $storeAction->execute($request->validated());

        return to_route('roles.index');
    }

    public function edit(string $id): Response|RedirectResponse
    {
        $role = Role::with('permissions')->find($id);
        $allPermissions = Permission::all();

        if (in_array($role->getAttribute('name'), Roles::getAllRoles())) {
            return to_route('roles.index');
        }

        return Inertia::render('Roles/Edit', compact('role', 'allPermissions'));
    }

    public function update(UpdateRoleRequest $request, string $id, UpdateRoleAction $updateAction): RedirectResponse
    {
        $updateAction->execute($id, $request->validated());

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role, DestroyRoleAction $roleAction): RedirectResponse
    {
        $result = $roleAction->execute($role);

        if ($result === false) {
            return redirect()->back();
        }

        return redirect()->route('roles.index');
    }

}
