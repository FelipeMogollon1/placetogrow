<?php

namespace App\Domain\Category\Actions;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;

class DestroyCategoryAction
{
    public function execute(string $id): bool
    {
        $category = Category::findOrFail($id);

        $micrositeCount = Microsite::where('category_id', $category->id)->count();

        if ($micrositeCount > 0) {
            return false;
        }

        return $category->delete();
    }

}
