<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'token',
        'sub_token',
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
}
