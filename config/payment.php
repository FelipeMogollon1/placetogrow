<?php

use App\PaymentGateway\PlacetopayGateway;

return [
    'services' => [
        'current' => env('CURRENT_GATEWAY', 'placetopay'),
        'placetopay' => [
            'class' => PlacetopayGateway::class,
            'settings' => [
                'login' => env('PLACETOPAY_LOGIN'),
                'tranKey' => env('PLACETOPAY_TRANKEY'),
                'baseUrl' => env('PLACETOPAY_BASE_URL'),
                'timeout' => env('PLACETOPAY_TIME_OUT')
            ]
        ],

    ]
];
