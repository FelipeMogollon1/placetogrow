<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Models\Category;

use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Database\Eloquent\Model;

class EloquentCategoryReponsitory implements CategoryRepositoryInterface
{
    protected Model $category;

    public function __construct(category $category)
    {
        $this->category = $category;
    }

    public function getAllWithCategories(): iterable
    {
        return Category::select(['id', 'name', 'description'])->get();
    }

    public function delete(int $id): bool
    {
        $category = Category::findOrFail($id);

        $micrositeCount = Microsite::where('category_id', $category->id)->count();

        if ($micrositeCount > 0) {
            return false;
        }

        return $category->delete();
    }

    public function find(int $id): ?Category
    {
        return $this->category->find($id);
    }
}
