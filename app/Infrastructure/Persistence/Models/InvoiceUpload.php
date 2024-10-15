<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceUpload extends Model
{
    use HasFactory;

    protected $table = 'invoice_uploads';

    protected $fillable = [
        'user_id',
        'microsite_id',
        'expiration_date',
        'storage_path',
        'error_file_path',
        'valid_records_count',
        'total_records',
        'status'
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
