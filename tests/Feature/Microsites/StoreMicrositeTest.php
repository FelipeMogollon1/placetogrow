<?php

namespace Tests\Feature\Microsites;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_microsites(): void
    {
        $this->seed();

        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole(Roles::SA->value);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::MICROSITES_STORE->value);

        $logo = UploadedFile::fake()->image('logo.jpg');

        $category = Category::factory()->create();

        $microsite = [
            'name' => 'andres',
            'logo' => $logo,
            'document_type' => DocumentTypes::CC->value,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE->value,
            'currency' => CurrencyTypes::COP->value,
            'payment_expiration_time' => 12,
            'category_id' => $category->id,
            'user_id'=> $user->id
        ];

        $response = $this->actingAs($user)
            ->post(route('microsites.store'), $microsite);

        $response->assertRedirect(route('microsites.index'));

        Storage::disk('public')->assertExists('logo/' . $logo->hashName());

        $this->assertDatabaseHas('microsites', [
            'name' => 'andres',
            'logo' => 'logo/' . $logo->hashName(),
            'document_type' => DocumentTypes::CC->value,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE->value,
            'currency' => CurrencyTypes::COP->value,
            'payment_expiration_time' => 12,
            'category_id' => $category->id,
            'user_id'=> $user->id
        ]);
    }
}
