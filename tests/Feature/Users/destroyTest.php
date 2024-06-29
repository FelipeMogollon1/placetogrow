<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class destroyTest extends TestCase
{

    public function test_can_destroy_use(): void
    {
        $user = User::factory()->create();

         $this->actingAs($user)
                ->delete(route('users.destroy', $user->id))
                ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
