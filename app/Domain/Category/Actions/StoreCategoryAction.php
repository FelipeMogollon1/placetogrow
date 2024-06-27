<?php

namespace App\Domain\Category\Actions;

use App\Infrastructure\Persistence\Models\Category;
use Exception;
use Illuminate\Http\RedirectResponse;

class StoreCategoryAction
{
    public function execute(array $data): RedirectResponse
    {
        Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return to_route('categories.index');
    }
}
