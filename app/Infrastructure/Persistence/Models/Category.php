<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $guarded = [];

    protected $fillable = [
        'id',
        'name',
        'description',
        'enabled_at',
    ];

    public function microsites(): hasMany
    {
        return $this->hasMany(Microsite::class);
    }



}
