<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $categories = [
            [
                'name' => 'essential',
                'description' => 'Category for essential items',
            ],
            [
                'name' => 'invoice',
                'description' => 'Category for invoices',
            ],
            [
                'name' => 'subscription',
                'description' => 'Category for subscriptions',
            ],
        ];

        Category::query()->insert($categories);
    }
}
