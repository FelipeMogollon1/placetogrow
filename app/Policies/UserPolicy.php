<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Infrastructure\Persistence\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_INDEX);

    }

    public function store(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_STORE);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_EDIT);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_CREATE);

    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_UPDATE);
    }


    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_DESTROY);
    }

}
