<?php

namespace App\Domain\Category\Actions;

use App\Infrastructure\Persistence\Models\Category;

class StoreCategoryAction
{
    public function execute(array $data): ?Category
    {
        return Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }
}
