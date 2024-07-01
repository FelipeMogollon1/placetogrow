<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permissions::getAllPermissions() as $permission) {
            $permissions[] = ['name' => $permission, 'guard_name' => 'web'];
        }

        DB::table('permissions')->upsert($permissions, 'name');
    }
}
