<?php

return [
    'days_before_invoice_expiration' => env('NOTIFICATION_DAYS_BEFORE_INVOICE_EXPIRATION', 5),
    'days_before_subscription_expiration' => env('NOTIFICATION_DAYS_BEFORE_SUBSCRIPTION_EXPIRATION', 5),
    'days_before_subscription_collection' => env('NOTIFICATION_DAYS_BEFORE_SUBSCRIPTION_COLLECTION', 5),
];
