<?php

namespace App\Providers;

use App\Contracts\CloudComputeInterface;
use App\Contracts\DomainRegistrarInterface;
use App\Contracts\HostingPanelInterface;
use App\Services\CwpService;
use App\Services\GoogleComputeService;
use App\Services\InternetBsService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DomainRegistrarInterface::class, fn () => new InternetBsService());
        $this->app->singleton(HostingPanelInterface::class, fn () => new CwpService());
        $this->app->singleton(CloudComputeInterface::class, fn () => new GoogleComputeService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || str_starts_with((string) config('app.url'), 'https://') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
