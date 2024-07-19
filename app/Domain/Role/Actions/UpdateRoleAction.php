<?php

namespace App\Domain\Role\Actions;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UpdateRoleAction
{
    public function execute(int $id, array $data): bool
    {
        $user = Auth::user();
        $role = Role::findOrFail($id);

        if (!$user->hasPermissionTo(Permissions::ROLES_UPDATE)) {
            return false;
        }

        if (in_array($role->getAttribute('name'), Roles::getAllRoles())) {
            return false;
        }

        $role->update([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return true;
    }
}
