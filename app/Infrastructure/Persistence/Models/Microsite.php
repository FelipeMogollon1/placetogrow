<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\MicrositeFactory;
use Illuminate\Database\Eloquent\Builder;
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
        'user_id',
        'enabled_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeWithCategory(Builder $query, $id = null): Builder
    {
        return $query->with(['category', 'user'])->where('id', $id);

    }

    public function scopeAllWithCategories(Builder $query): Builder
    {
        return $query
            ->leftJoin('categories', 'microsites.category_id', '=', 'categories.id')
            ->leftJoin('users', 'microsites.user_id', '=', 'users.id')
            ->select([
                'microsites.id',
                'microsites.name',
                'microsites.microsite_type',
                'microsites.logo',
                'categories.name as category_name',
                'users.name as user_name',
            ]);
    }

    protected static function newFactory(): Factory|MicrositeFactory
    {
        return MicrositeFactory::new();
    }

}
