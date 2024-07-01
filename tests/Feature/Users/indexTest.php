<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class indexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_index_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('users.index'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                ->component('Users/Index')
                ->has('users')
            );
    }


}
