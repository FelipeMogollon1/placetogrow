<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class EditMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_microsite(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $microsite = Microsite::factory()->create();

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::MICROSITES_EDIT);

        $response = $this->actingAs($user)
            ->get(route('microsites.edit', $microsite->id));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Edit')
            );
    }
}
