<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Form;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionsSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MicrositeSeeder::class);
    }
}
