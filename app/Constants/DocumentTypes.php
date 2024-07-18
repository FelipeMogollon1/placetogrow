<?php

namespace App\Constants;

enum DocumentTypes: string
{
    case CC = 'CC';
    case NIT = 'NIT';
    case NIP = 'NIP';
    case TI = 'TI';
    case CE = 'CE';
    case PPT = 'PPT';

    public static function getDocumentTypes(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
