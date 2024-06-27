<?php

namespace App\Domain\Category\Actions;

use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Http\RedirectResponse;

class UpdateCategoryAction
{
    public function execute(int $id, array $data): RedirectResponse
    {
        Category::findOrFail($id)->update($data);

        return to_route('categories.index');
    }
}
