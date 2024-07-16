<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_microsites(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::MICROSITES_CREATE);

        $response = $this->actingAs($user)
            ->get(route('microsites.create'));

        $response->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Microsites/Create'));
    }
}
