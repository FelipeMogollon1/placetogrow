<?php

namespace App\Domain\Microsite\Repositories;

use App\Infrastructure\Persistence\Models\Microsite;

interface MicrositeRepositoryInterface
{
    public function getAllWithCategories(): iterable;
    public function getCommonData(): array;
    public function getWithCategories(int $id): array;

}
