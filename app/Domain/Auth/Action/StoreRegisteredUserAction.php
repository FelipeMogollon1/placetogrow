<?php

namespace App\Domain\Auth\Action;

use App\Infrastructure\Persistence\Models\User;
use App\Constants\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class StoreRegisteredUserAction
{
    public function execute(array $array): void
    {
        $user = User::create([
            'name' => $array['name'],
            'surname' => $array['surname'],
            'document_type' => $array['document_type'],
            'document' => $array['document'],
            'email' => $array['email'],
            'password' => Hash::make($array['password']),
        ]);

        $user->assignRole($data['role'] ?? Roles::GUEST->value);

        event(new Registered($user));

        Auth::login($user);
    }
}
