<?php

namespace App\Domain\Role\Actions;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DestroyRoleAction
{
    public function execute(Role $role): bool
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo(Permissions::ROLES_DESTROY)) {
            return false;
        }

        if (!in_array($role->getAttribute('name'), Roles::getAllRoles())) {
            $role->delete();
            return true;
        }

        return false;
    }
}
