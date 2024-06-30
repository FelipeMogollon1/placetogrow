<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    protected array $roles =[
        ['name' => Roles::ADMIN, 'guard_name' => 'web'],
        ['name' => Roles::GUEST, 'guard_name' => 'web'],
    ];

    public function run(): void
    {
        DB::table('roles')->upsert($this->roles,'name');

        $this->assignPermissionsToAdmin();
        $this->assignPermissionsToGuest();
    }

    public function  assignPermissionsToAdmin(): void
    {
        $adminRole = Role::findByName(Roles::ADMIN);

        $adminRole->syncPermissions(Permissions::getAllPermissions());
    }

    public function assignPermissionsToGuest(): void
    {
        $guestRole = Role::findByName(Roles::GUEST);

        $guestRole->syncPermissions(Permissions::getAllPermissions());
    }
}
