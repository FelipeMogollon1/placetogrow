<?php

namespace App\Domain\Category\Actions;

use App\Infrastructure\Persistence\Models\Category;

class UpdateCategoryAction
{
    public function execute(int $id, array $data): ?bool
    {
        return Category::findOrFail($id)->update($data);
    }
}
