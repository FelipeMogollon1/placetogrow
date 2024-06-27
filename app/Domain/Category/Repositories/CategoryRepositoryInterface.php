<?php

namespace App\Domain\Category\Repositories;

use App\Infrastructure\Persistence\Models\Category;

interface CategoryRepositoryInterface
{
    public function find(int $id): ?Category;
    public function getAllWithCategories(): iterable;
    public function delete(int $id): bool;
}
