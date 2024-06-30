<?php

namespace Tests\Feature\Microsites;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class showTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_show_microsite(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $logo = UploadedFile::fake()->image('logo.jpg');
        $logoPath = $logo->store('logo', ['disk' => 'public']);

        $category = Category::factory()->create();

        $microsite = Microsite::factory()->create([
            'name' => 'andres',
            'logo' => $logoPath,
            'document_type' => DocumentTypes::CC,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE,
            'currency' => CurrencyTypes::COP,
            'payment_expiration_time' => 12,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('microsites.show', $microsite->id));

        $response->assertOk()
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Microsites/Show')
                    ->has('microsite', fn($microsite) =>
                    $microsite
                        ->where('name', 'andres')
                        ->where('logo', $logoPath)
                        ->where('document_type', DocumentTypes::CC)
                        ->where('document', '1321657')
                        ->where('microsite_type', MicrositesTypes::INVOICE)
                        ->where('currency', CurrencyTypes::COP)
                        ->where('payment_expiration_time', 12)
                        ->where('category_id', $category->id)
                        ->etc()
                    )
            );
    }
}
