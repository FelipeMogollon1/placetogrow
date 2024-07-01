<?php

namespace App\Constants;

class Permissions
{
    public const USERS_INDEX = 'users.index';
    public const USERS_STORE = 'users.store';
    public const USERS_CREATE = 'users.create';
    public const USERS_EDIT  = 'users.edit';
    public const USERS_UPDATE  = 'users.update';
    public const USERS_DESTROY = 'users.destroy';


    public const MICROSITES_INDEX = 'microsites.index';
    public const MICROSITES_SHOW = 'microsites.show';
    public const MICROSITE_CREATE = 'microsites.create';
    public const MICROSITES_STORE = 'microsites.store';
    public const MICROSITES_EDIT  = 'microsites.edit';
    public const MICROSITES_UPDATE  = 'microsites.update';
    public const MICROSITES_DESTROY = 'microsites.destroy';


    public const CATEGORIES_INDEX = 'categories.index';
    public const CATEGORIES_CREATE = 'categories.create';
    public const CATEGORIES_STORE = 'categories.store';
    public const CATEGORIES_EDIT  = 'categories.edit';
    public const CATEGORIES_UPDATE  = 'categories.update';
    public const CATEGORIES_DESTROY = 'categories.destroy';

    public const ROLES_INDEX = 'roles.index';
    public const ROLES_CREATE = 'roles.create';
    public const ROLES_STORE = 'roles.store';
    public const ROLES_EDIT  = 'roles.edit';
    public const ROLES_UPDATE  = 'roles.update';
    public const ROLES_DESTROY = 'roles.destroy';

    public static function getAllPermissions(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

    public static function getGuestPermissions(): array
    {
        return [
          self::MICROSITES_INDEX,
        ];
    }
}
