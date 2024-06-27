<?php

namespace App\Infrastructure\Persistence\Models;

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

}
