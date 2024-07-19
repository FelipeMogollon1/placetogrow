<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DestroyMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_destroy_microsite(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $microsite = Microsite::factory()->create();

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::MICROSITES_DESTROY);

        $response = $this->actingAs($user)
            ->delete(route('microsites.destroy', $microsite->id));

        $response->assertRedirect(route('microsites.index'));
        $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);

        if ($microsite->logo) {
            Storage::disk('public')->assertMissing($microsite->logo);
        }
    }
}
