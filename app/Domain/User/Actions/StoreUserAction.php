<?php

namespace App\Domain\User\Actions;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class StoreUserAction
{
    public function execute(array $data): RedirectResponse
    {

        $user = User::query()->create($data);
        $user->assignRole($data['role']);

        return to_route('users.index');
    }
}
