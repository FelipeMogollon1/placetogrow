<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{

    public function register(): void
    {

    }

    public function boot()
    {
            $this->app->bind(PaymentGatewayContract::class, function () {
            $service = config('payment.services.current');
            $gateway = config('payment.services.'.$service);
            $gatewayClass = Arr::get($gateway, 'class');
            return (new $gatewayClass())->connection(Arr::get($gateway, 'settings'));
        });
    }
}
