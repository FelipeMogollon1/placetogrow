<?php

namespace Tests\Feature\Microsites;

use App\Infrastructure\Persistence\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class destroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_destroy_microsite(): void
    {
         $user = User::factory()->create();
        $microsite = Microsite::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('microsites.destroy', $microsite->id));

        $response->assertRedirect(route('microsites.index'));

        $this->assertDatabaseMissing('microsites', ['id' => $microsite->id]);

        if ($microsite->logo) {
            Storage::disk('public')->assertMissing($microsite->logo);
        }
    }
}
