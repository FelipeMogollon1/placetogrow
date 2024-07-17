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

    public function getAllWithCategories(): iterable
    {
        return Microsite::query()
            ->join('categories', 'microsites.category_id', '=', 'categories.id')
            ->select([
                'microsites.id',
                'microsites.name',
                'microsites.microsite_type',
                'microsites.logo',
                'categories.name as category_name'
            ])
            ->get();
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

    public function getWithCategories(int $id): array
    {
        return Microsite::with('category')
            ->findOrFail($id)
            ->toArray();
    }

}
