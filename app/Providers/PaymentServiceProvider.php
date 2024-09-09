<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(PaymentGatewayContract::class, function () {
            $service = config('payment.services.current');

            return (app(PlacetopayGateway::class))
                ->connection(Arr::get(config('payment.services.'.$service), 'settings'));
        });
    }
}
