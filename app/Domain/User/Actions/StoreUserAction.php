<?php

namespace App\Domain\User\Actions;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class StoreUserAction
{
    public function execute(array $data): RedirectResponse
    {

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return to_route('users.index');
    }
}
