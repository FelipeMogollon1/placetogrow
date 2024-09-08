<?php

namespace App\Infrastructure\Persistence\Models;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\PaymentStatus;
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
    ];

    protected $casts = [
        'document_type' => DocumentTypes::class,
        'currency_type' => CurrencyTypes::class,
        'status'        => PaymentStatus::class,
        'microsite_type' => MicrositesTypes::class,
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
