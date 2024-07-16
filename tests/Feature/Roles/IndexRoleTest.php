<?php

namespace Tests\Feature\Roles;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class IndexRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_index_rol(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->assertAuthenticatedAs($user)->get(route('roles.index'));
        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Roles/Index')
                    ->has('roles')
            );

    }
}
