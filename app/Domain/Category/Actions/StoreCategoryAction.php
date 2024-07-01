<?php

namespace App\Domain\Category\Actions;

use App\Infrastructure\Persistence\Models\Category;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class StoreCategoryAction
{
    public function execute(array $data): RedirectResponse
    {
        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        Log::info('Category created successfully', [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
        ]);

        return to_route('categories.index');
    }
}
