<?php

namespace App\Constants;

enum InvoiceUploadStatus: String
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case COMPLETED_WITH_ERRORS = 'completed_with_errors';

    public static function getInvoiceUploadStatus(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
