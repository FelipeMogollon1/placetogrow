<?php

namespace Database\Factories;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Microsite>
 */
class MicrositeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Microsite::class;


    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug(),
            'name' => $this->faker->name(),
            'logo' => $this->faker->imageUrl(),
            'document_type' => $this->faker->randomElement(DocumentTypes::getDocumentTypes()),
            'document' => Str::random(10),
            'microsite_type' => $this->faker->randomElement(MicrositesTypes::getMicrositesTypes()),
            'currency' => $this->faker->randomElement(CurrencyTypes::getCurrencyType()),
            'payment_expiration_time' => $this->faker->numberBetween(3, 100),
            'category_id' => Category::factory()->create()->id,
        ];
    }
}
