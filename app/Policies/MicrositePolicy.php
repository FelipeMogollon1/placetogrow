<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;


class MicrositePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITES_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITES_SHOW);
    }

    public function store(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITES_STORE);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITES_EDIT);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITE_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITES_UPDATE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::MICROSITES_DESTROY);
    }

}
