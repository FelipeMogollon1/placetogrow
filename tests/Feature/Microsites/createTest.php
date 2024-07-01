<?php

namespace Tests\Feature\Microsites;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class createTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_microsites(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('microsites.create'));

        $response->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Microsites/Create'));
    }
}
