<?php

namespace App\Domain\Role\Actions;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class UpdateRoleAction
{
    public function execute(int $id,array $data): RedirectResponse
    {
        $role = Role::find($id);

        $role->update($data);

        return to_route('roles.index');
    }
}
