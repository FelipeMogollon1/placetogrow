<?php

namespace Tests\Feature\Microsites;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShowMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_show_microsite(): void
    {
        $this->seed();

        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::MICROSITES_SHOW);

        $logo = UploadedFile::fake()->image('logo.jpg');
        $logoPath = $logo->store('logo', ['disk' => 'public']);

        $category = Category::factory()->create();

        $microsite = Microsite::factory()->create([
            'name' => 'andres',
            'logo' => $logoPath,
            'document_type' => DocumentTypes::CC->value,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE->value,
            'currency' => CurrencyTypes::COP->value,
            'payment_expiration_time' => 12,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('microsites.show', $microsite->id));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Microsites/Show')
                    ->has(
                        'microsite',
                        fn ($microsite) =>
                        $microsite
                            ->where('name', 'andres')
                            ->where('logo', $logoPath)
                            ->where('document_type', DocumentTypes::CC->value)
                            ->where('document', '1321657')
                            ->where('microsite_type', MicrositesTypes::INVOICE->value)
                            ->where('currency', CurrencyTypes::COP->value)
                            ->where('payment_expiration_time', 12)
                            ->where('category_id', $category->id)
                            ->etc()
                    )
            );
    }

}
