<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\FormFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $guarded = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'configuration',
        'header',
        'footer',
        'color',
        'additional_info',
        'expiration_additional_info',
        'tries',
        'backoff',
        'additionalValue',
        'additionalValueType'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'configuration' => 'array',
        'header' => 'string',
        'footer' => 'string',
        'color' => 'string',
        'tries' => 'integer',
        'backoff' => 'integer',
        'additionalValue' => 'integer',
        'additionalValueType' => 'string',
    ];

    public function microsite(): HasOne
    {
        return $this->hasOne(Microsite::class);
    }

    protected static function newFactory(): FormFactory
    {
        return FormFactory::new();
    }
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
