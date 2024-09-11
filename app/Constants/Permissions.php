<?php

namespace App\Constants;

enum Permissions: string
{
    case USERS_INDEX = 'users.index';
    case USERS_STORE = 'users.store';
    case USERS_CREATE = 'users.create';
    case USERS_EDIT = 'users.edit';
    case USERS_UPDATE = 'users.update';
    case USERS_DESTROY = 'users.destroy';


    case MICROSITES_INDEX = 'microsites.index';
    case MICROSITES_SHOW = 'microsites.show';
    case MICROSITES_CREATE = 'microsites.create';
    case MICROSITES_STORE = 'microsites.store';
    case MICROSITES_EDIT = 'microsites.edit';
    case MICROSITES_UPDATE = 'microsites.update';
    case MICROSITES_DESTROY = 'microsites.destroy';


    case CATEGORIES_INDEX = 'categories.index';
    case CATEGORIES_CREATE = 'categories.create';
    case CATEGORIES_STORE = 'categories.store';
    case CATEGORIES_EDIT = 'categories.edit';
    case CATEGORIES_UPDATE = 'categories.update';
    case CATEGORIES_DESTROY = 'categories.destroy';


    case ROLES_INDEX = 'roles.index';
    case ROLES_CREATE = 'roles.create';
    case ROLES_STORE = 'roles.store';
    case ROLES_EDIT = 'roles.edit';
    case ROLES_UPDATE = 'roles.update';
    case ROLES_DESTROY = 'roles.destroy';


    case PAYMENTS_INDEX = 'payments.index';
    case PAYMENTS_SHOW = 'payments.show';


    case SUBSCRIPTIONS_INDEX = 'subscriptions.index';
    case SUBSCRIPTIONS_SHOW = 'subscriptions.show';
    case SUBSCRIPTIONS_EDIT= 'subscriptions.edit';
    case SUBSCRIPTIONS_UPDATE  = 'subscriptions.update';
    case SUBSCRIPTIONS_DESTROY  = 'subscriptions.destroy';


    case INVOICES_INDEX = 'invoices.index';
    case INVOICES_IMPORT = 'invoices.import';
    case INVOICES_SHOW = 'invoices.show';
    case INVOICES_DESTROY  = 'invoices.destroy';
    case INVOICE_PROCESS_PAYMENT = 'invoices.processPayment';



    public static function getAllPermissions(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }

    public static function getAdminPermissions(): array
    {
        return [
            self::MICROSITES_INDEX->value,
            self::MICROSITES_SHOW->value,
            self::PAYMENTS_INDEX->value,
            self::PAYMENTS_SHOW->value,
            self::SUBSCRIPTIONS_INDEX->value,
            self::SUBSCRIPTIONS_SHOW->value,
            self::INVOICES_INDEX->value,
            self::INVOICES_SHOW->value,
            self::INVOICES_IMPORT->value,
            self::INVOICES_DESTROY->value
        ];
    }

    public static function getGuestPermissions(): array
    {
        return [
            self::PAYMENTS_INDEX->value,
            self::PAYMENTS_SHOW->value,
            self::SUBSCRIPTIONS_INDEX->value,
            self::SUBSCRIPTIONS_SHOW->value,
            self::SUBSCRIPTIONS_DESTROY->value,
            self::INVOICES_INDEX->value,
            self::INVOICES_SHOW->value,
            self::INVOICE_PROCESS_PAYMENT->value
        ];
    }
}
