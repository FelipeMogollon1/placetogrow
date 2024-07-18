<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\MicrositeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Microsite extends Model
{
    use HasFactory;

    protected $table = 'microsites';

    protected $guarded = [];

    protected $fillable = [
        'slug',
        'name',
        'logo',
        'document_type',
        'document',
        'microsite_type',
        'currency',
        'payment_expiration_time',
        'category_id',
        'enabled_at',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeWithCategory($query, $id = null)
    {
        if ($id !== null) {
            return $query->with('category')->where('id', $id);
        }

        return $query->join('categories', 'microsites.category_id', '=', 'categories.id')
            ->select([
                'microsites.id',
                'microsites.name',
                'microsites.microsite_type',
                'microsites.logo',
                'categories.name as category_name'
            ]);
    }

    protected static function newFactory(): Factory|MicrositeFactory
    {
        return MicrositeFactory::new();
    }


}
