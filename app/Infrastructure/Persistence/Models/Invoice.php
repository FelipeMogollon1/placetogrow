<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;


    protected $fillable = [
        'reference',
        'name',
        'surname',
        'email',
        'document_type',
        'document',
        'description',
        'currency_type',
        'amount',
        'status',
        'user_id',
        'microsite_id',
        'microsite_type',
        'receipt',
        'process_url',
        'request_id',
        'process_identifier',
        'expiration_date',
    ];

    protected $casts = [
        'document_type' => 'string',
        'currency_type' => 'string',
        'status'        => 'string',
        'microsite_type' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }
}
