<?php

namespace App\Providers;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Repositories\Eloquent\LayerRepository;
use App\Repositories\Eloquent\LayupRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(LayupRepositoryInterface::class, LayupRepository::class);
        $this->app->bind(LayerRepositoryInterface::class, LayerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-clt-data', fn ($user) => $user !== null);
    }
}
