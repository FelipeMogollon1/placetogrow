<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->upsert($this->generateUsers(), 'email');

        User::query()->where('email', 'sa@microsites.com')->first()->assignRole(Roles::SA);
        User::query()->where('email', 'admin@microsites.com')->first()->assignRole(Roles::ADMIN);
        User::query()->where('email', 'guest@microsites.com')->first()->assignRole(Roles::GUEST);

    }

    public function generateUsers(): array
    {
        return [
            [
                'name' => 'super administrator',
                'surname' => 'sa',
                'email' => 'sa@microsites.com',
                'document_type' => 'CC',
                'document' => '111111111',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'administrator',
                'surname' => 'admin',
                'email' => 'admin@microsites.com',
                'document_type' => 'CC',
                'document' => '222222222',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guest',
                'surname' => 'guest',
                'email' => 'guest@microsites.com',
                'document_type' => 'CC',
                'document' => '333333333',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

    }
}
