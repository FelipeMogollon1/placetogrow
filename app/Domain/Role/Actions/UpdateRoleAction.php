<?php

namespace App\Domain\Role\Actions;

use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class UpdateRoleAction
{
    public function execute(int $id, array $data): RedirectResponse
    {
        $role = Role::findOrFail($id);

        $role->update([
           'name' => $data['name'],
           'guard_name' => 'web',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return to_route('roles.index');
    }
}
