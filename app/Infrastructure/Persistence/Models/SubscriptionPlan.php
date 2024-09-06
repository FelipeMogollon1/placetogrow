<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'amount',
        'currency',
        'subscription_period',
        'expiration_time',
        'additional_info',
        'expiration_additional_info',
        'microsite_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'array',
        'amount' => 'decimal:2',
        'subscription_period' => 'string',
    ];

    /**
     * Get the microsite that owns the subscription plan.
     */
    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

}
