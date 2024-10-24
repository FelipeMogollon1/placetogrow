<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(PaymentGatewayContract::class, function ($app) {

            $service = config('payment.services.current');
            $settings = config('payment.services.' . $service . '.settings');

            return new PlacetopayGateway($settings);
        });
    }
}
