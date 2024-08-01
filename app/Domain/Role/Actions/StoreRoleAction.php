<?php

namespace App\Domain\Role\Actions;

use Spatie\Permission\Models\Role;

class StoreRoleAction
{
    public function execute(array $data): Role
    {
        return Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ])->syncPermissions($data['permissions'] ?? []);
    }
}
