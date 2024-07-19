<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    protected array $roles = [
        ['name' => Roles::SA, 'guard_name' => 'web'],
        ['name' => Roles::ADMIN, 'guard_name' => 'web'],
        ['name' => Roles::GUEST, 'guard_name' => 'web'],
    ];

    public function run(): void
    {
        DB::table('roles')->upsert($this->roles, 'name');

        $this->assignPermissionsToSA();
        $this->assignPermissionsToAdmin();
        $this->assignPermissionsToGuest();
    }

    public function assignPermissionsToSA(): void
    {
        $superAdminRole = Role::findByName(Roles::SA->value);

        $superAdminRole->syncPermissions(Permissions::getAllPermissions());
    }

    public function assignPermissionsToAdmin(): void
    {
        $adminRole = Role::findByName(Roles::ADMIN->value);

        $adminRole->syncPermissions(Permissions::getAdminPermissions());
    }

    public function assignPermissionsToGuest(): void
    {
        $guestRole = Role::findByName(Roles::GUEST->value);

        $guestRole->syncPermissions(Permissions::getGuestPermissions());
    }


}
