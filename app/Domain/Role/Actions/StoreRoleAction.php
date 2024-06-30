<?php

namespace App\Domain\Role\Actions;

use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class StoreRoleAction
{
    public function execute(array $data): RedirectResponse
    {

        Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ])->syncPermissions($data['permissions'] ?? []);

        return to_route('roles.index');
    }
}
