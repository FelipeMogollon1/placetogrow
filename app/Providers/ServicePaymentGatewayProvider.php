<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class ServicePaymentGatewayProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the service with the appropriate configuration
      /*  $this->app->bind(PaymentGatewayContract::class, function ($app) {
            $service = config('payment.services.current');
            $gatewayConfig = config('payment.services.' . $service);
            $gatewayClass = Arr::get($gatewayConfig, 'class');
            $settings = Arr::get($gatewayConfig, 'settings');

            // Instantiate the gateway and configure it
            $instance = new $gatewayClass();
            $instance->connection($settings);

            return $instance;

        });*/



    }
}
