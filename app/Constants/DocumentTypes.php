<?php

namespace App\Constants;

enum DocumentTypes: string
{
    case CC = 'CC';
    case CE = 'CE';
    case TI = 'TI';
    case NIT = 'NIT';

    public static function getDocumentTypes(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
