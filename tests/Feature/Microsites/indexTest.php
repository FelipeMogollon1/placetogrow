<?php

namespace Tests\Feature\Microsites;

use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use App\Models\User;
use Tests\TestCase;

class indexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_index_microsites(): void
    {
        $user = User::factory()->create();
        $microsites = Microsite::factory()->create();

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('microsites.index'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Index', ['microsites' => $microsites])
                    ->has('microsites')
            );
    }
}
