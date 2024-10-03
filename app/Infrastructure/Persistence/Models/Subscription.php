<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'description',
        'name',
        'surname',
        'email',
        'document_type',
        'document',
        'process_url',
        'request_id',
        'mobile',
        'company',
        'paid_at',
        'token',
        'sub_token',
        'franchiseName',
        'lastDigits',
        'validUntil',
        'subscription_plan_id',
        'microsite_id',
        'status',
    ];

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }
}
