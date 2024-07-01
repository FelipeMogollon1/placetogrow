<?php

namespace Tests\Feature\Microsites;

use App\Infrastructure\Persistence\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class editTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_microsite(): void
    {

        $user = User::factory()->create();
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('microsites.edit', compact('microsite')));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Edit')
            );
    }
}
