<?php

namespace App\Http\Controllers\Roles;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Domain\Role\Actions\StoreRoleAction;
use App\Domain\Role\Actions\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        return Inertia::render('Roles/Index', ['roles' => Role::all()]);
    }

    public function create(): Response
    {
        $permissions =  Permission::all();
        return Inertia::render('Roles/Create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request, StoreRoleAction $storeAction): RedirectResponse
    {
        return $storeAction->execute($request->validated());
    }

    public function edit(string $id): Response
    {
        $role = Role::with('permissions')->find($id);
        $allPermissions = Permission::all();

        return Inertia::render('Roles/Edit', compact('role', 'allPermissions'));
    }

    public function update(UpdateRoleRequest $request, string $id, UpdateRoleAction $updateAction): RedirectResponse
    {
        return $updateAction->execute($id, $request->validated());
    }


    public function destroy(Role $role): RedirectResponse
    {
        return $this->validateDeleteBaseRole($role);
    }


    private function validateDeleteBaseRole(Role $role): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo(Permissions::ROLES_DESTROY)) {
            return redirect()->back();
        }

        if (!in_array($role->getAttribute('name'), Roles::getAllRoles())) {
            $role->delete();
            return redirect()->route('roles.index');
        }
        return redirect()->route('roles.index');
    }

}
