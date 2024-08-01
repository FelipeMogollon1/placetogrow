<?php

namespace Tests\Feature\Roles;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EditRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_role(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('super_admin', 'web');

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('roles.edit', $role->id));

        $response->assertOK()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Roles/Edit')
            );
    }
}
