<?php

namespace Database\Seeders;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MicrositeSeeder extends Seeder
{

    public function run(): void
    {
        $categories = Category::all();

        $microsites = [];

        for ($i = 1; $i <= 5; $i++) {

            $slug = Str::slug("Microsite $i") . '-' . Str::random(5);

            while (Microsite::where('slug', $slug)->exists()) {
                $slug = Str::slug("Microsite $i") . '-' . Str::random(5);
            }

            $microsite = [
                'name' => "Microsite $i",
                'slug' => $slug,
                'logo' => "",
                'document_type' => array_rand(array_flip(DocumentTypes::getDocumentTypes())),
                'document' => rand(1000000000, 9999999999),
                'microsite_type' => array_rand(array_flip(MicrositesTypes::getMicrositesTypes())),
                'currency' => array_rand(array_flip(CurrencyTypes::getCurrencyType())),
                'payment_expiration_time' => rand(30, 60),
                'category_id' => $categories->random()->id,
            ];

            $microsites[] = $microsite;
        }

        foreach ($microsites as $micrositeData) {
            Microsite::create($micrositeData);
        }
    }

}
