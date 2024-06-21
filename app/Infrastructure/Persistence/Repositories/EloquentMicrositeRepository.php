<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Microsite\Repositories\MicrositeRepositoryInterface;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Database\Eloquent\Model;

class EloquentMicrositeRepository implements MicrositeRepositoryInterface
{
    protected Model $microsite;

    public function __construct(Microsite $microsite)
    {
        $this->microsite = $microsite;
    }

    public function all(): iterable
    {
        return $this->microsite->all();
    }

    public function find(int $id): ?Microsite
    {
        return $this->microsite->find($id);
    }

    public function delete(int $id): bool
    {
        $microsite = $this->find($id);

        if ($microsite) {
            return $microsite->delete();
        }

        return false;
    }
}
