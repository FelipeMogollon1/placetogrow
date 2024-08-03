<?php

namespace App\Infrastructure\Persistence\Models;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference',
        'receipt',
        'payer_name',
        'payer_surname',
        'payer_email',
        'payer_phone',
        'payer_company',
        'payer_document_type',
        'payer_document',
        'description',
        'amount',
        'paid_at',
        'currency',
        'status',
        'process_url',
        'request_id',
        'process_identifier',
        'user_id',
        'microsite_id',
        'additional_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'additional_data' => 'array',
        'amount' => 'integer',
        'currency' => 'string',
        'status' => 'string',
        'payer_document_type' => 'string',
    ];

    /**
     * The attributes that should be treated as enums.
     *
     * @var array<string, string>
     */
    protected $enums = [
        'payer_document_type' => DocumentTypes::class,
        'currency' => CurrencyTypes::class,
        'status' => PaymentStatus::class,
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
