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

        User::query()->where('email', 'admin@microsites.com')->first()->assignRole(Roles::ADMIN);
        User::query()->where('email', 'guest@microsites.com')->first()->assignRole(Roles::GUEST);

    }

    public function generateUsers(): array
    {
        return [

            [
                'name' => 'Admin',
                'email' => 'admin@microsites.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guest',
                'email' => 'guest@microsites.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

    }
}
