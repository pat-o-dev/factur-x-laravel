<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel;

use Illuminate\Support\ServiceProvider;
use PatODev\FacturX\FacturXGenerator;

class FacturXServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/factur-x.php', 'factur-x');

        $this->app->singleton(FacturXGenerator::class, static fn () => new FacturXGenerator());
        $this->app->alias(FacturXGenerator::class, 'factur-x');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/factur-x.php' => config_path('factur-x.php'),
            ], 'factur-x-config');
        }
    }
}
