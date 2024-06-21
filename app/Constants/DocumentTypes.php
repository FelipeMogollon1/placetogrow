<?php

namespace App\Constants;

class  DocumentTypes
{
    public const CC = 'CC';
    public const NIT = 'NIT';
    public const NIP = 'NIP';
    public const TI = 'TI';
    public const CE = 'CE';
    public const PPT = 'PPT';

    public static function getDocumentTypes(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

}
