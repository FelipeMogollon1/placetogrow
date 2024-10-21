<?php

namespace Database\Seeders;

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
        $this->call(InvoiceSeeder::class);
        $this->call(SubscriptionPlanSeeder::class);
        $this->call(SubscriptionSeeder::class);
    }
}
