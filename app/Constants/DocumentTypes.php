<?php

namespace App\Constants;

enum DocumentTypes: string
{
    case CC = 'CC';
    case TI = 'TI';
    case CE = 'CE';
    case NIT = 'NIT';
    case PAS = 'PAS';
    case RC = 'RC';


    public static function getDocumentTypes(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
