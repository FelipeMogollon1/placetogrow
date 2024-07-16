<?php

namespace Tests\Feature\Roles;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    protected array $request;

    public function setUp(): void
    {
        parent::setUp();

        if (!Permission::where('name', 'microsites.index')->exists()) {

            Permission::create([
                'name' => 'microsites.index',
                'guard_name' => 'web'
            ]);
        }

        $role = Role::findOrCreate('super_admin', 'web');
        $role->givePermissionTo('microsites.index');

        $this->request = [
            'name' => 'Admin',
            'permissions' => ['microsites.index'],
        ];
    }

    public function test_can_update_role(): void
    {
        $user = User::factory()->create();

        $role = Role::findByName('super_admin', 'web');

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->put(route('roles.update', $role->id), $this->request);

        $response->assertRedirect(route('roles.index'));

    }
}
