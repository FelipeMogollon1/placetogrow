<?php

namespace App\Domain\Role\Actions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class StoreRoleAction
{
    public function execute(array $data): RedirectResponse
    {

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ])->syncPermissions($data['permissions'] ?? []);

        Log::info('Role created successfully', [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        return to_route('roles.index');
    }
}
