<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class CategoryPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CATEGORIES_INDEX);
    }

    public function store(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CATEGORIES_STORE);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CATEGORIES_EDIT);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CATEGORIES_CREATE);
    }


    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CATEGORIES_UPDATE);
    }


    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CATEGORIES_DESTROY);

    }

}
