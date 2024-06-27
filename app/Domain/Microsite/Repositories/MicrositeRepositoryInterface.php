<?php

namespace App\Domain\Microsite\Repositories;

use App\Infrastructure\Persistence\Models\Microsite;

interface MicrositeRepositoryInterface
{
    public function all(): iterable;
    public function getAllWithCategories(): iterable;
    public function find(int $id): ?Microsite;
    public function delete(int $id): bool;
    public function getCommonData(): array;

}
