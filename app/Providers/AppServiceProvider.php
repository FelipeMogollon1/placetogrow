<?php

namespace App\Providers;

use App\Domain\Microsite\Repositories\MicrositeRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\EloquentMicrositeRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MicrositeRepositoryInterface::class, EloquentMicrositeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
