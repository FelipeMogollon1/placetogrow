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

    public function getAllWithCategories(): iterable
    {
        return Microsite::query()
            ->join('categories', 'microsites.category_id', '=', 'categories.id')
            ->select([
                'microsites.id',
                'microsites.name',
                'microsites.document_type',
                'microsites.document',
                'microsites.created_at',
                'microsites.currency',
                'microsites.payment_expiration_time',
                'microsites.microsite_type',
                'categories.name as category_name'
            ])
            ->get();
    }
}
