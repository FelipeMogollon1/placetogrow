<?php

namespace App\Constants;

enum Abilities: string
{
    case VIEW_ANY = 'viewAny';
    case VIEW = 'view';
    case STORE = 'store';
    case EDIT = 'edit';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';

    public static function getAllAbilities(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
