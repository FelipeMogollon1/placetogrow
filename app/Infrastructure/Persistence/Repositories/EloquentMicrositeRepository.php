<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Domain\Microsite\Repositories\MicrositeRepositoryInterface;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        $microsite = $this->findOrFail($id);

        if ($microsite->logo) {
            Storage::disk('public')->delete($microsite->logo);
        }

        return $microsite->delete();
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
                'microsites.currency',
                'microsites.payment_expiration_time',
                'microsites.microsite_type',
                'microsites.logo',
                'categories.name as category_name'
            ])
            ->get();
    }

    private function findOrFail(int $id)
    {
        $microsite = $this->microsite->find($id);

        if (!$microsite) {
            return false;
        }

        return $microsite;
    }

    public function getCommonData(): array
    {
        return [
            'documentTypes' => DocumentTypes::getDocumentTypes(),
            'micrositesTypes' => MicrositesTypes::getMicrositesTypes(),
            'currencyTypes' => CurrencyTypes::getCurrencyType(),
            'categories' => Category::select('id', 'name')->get(),
        ];
    }
}
