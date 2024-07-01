<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ROLES_INDEX);
    }

    public function store(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ROLES_STORE);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ROLES_EDIT);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ROLES_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ROLES_UPDATE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ROLES_DESTROY);
    }

}
