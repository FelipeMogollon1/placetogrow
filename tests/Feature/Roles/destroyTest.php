<?php

namespace Tests\Feature\Roles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class destroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_destroy_role(): void
    {
        $user = User::factory()->create();

        $role = Role::findOrCreate('super_admin','web');

        $response = $this->actingAs($user)
            ->delete(route('roles.destroy', $role->id));

        $response->assertRedirect(route('roles.index'));

    }
}
