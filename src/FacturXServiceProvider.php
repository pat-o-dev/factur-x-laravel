<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;
use PatODev\FacturX\FacturXGenerator;
use PatODev\FacturX\Laravel\Rendering\BladeInvoiceRenderer;
use PatODev\FacturX\Laravel\Rendering\InvoiceHtmlRenderer;
use PatODev\FacturX\Laravel\Rendering\InvoicePdfARenderer;
use PatODev\FacturX\Laravel\Rendering\MpdfInvoicePdfRenderer;

class FacturXServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/factur-x.php', 'factur-x');

        $this->app->singleton(FacturXGenerator::class, static fn () => new FacturXGenerator());
        $this->app->alias(FacturXGenerator::class, 'factur-x');

        $this->app->singleton(InvoiceHtmlRenderer::class, static fn ($app) => new BladeInvoiceRenderer(
            $app->make(ViewFactory::class),
            (string) $app->make('config')->get('factur-x.view'),
        ));

        $this->app->singleton(InvoicePdfARenderer::class, static fn ($app) => new MpdfInvoicePdfRenderer(
            (string) $app->make('config')->get('factur-x.pdfa_version'),
            (array) $app->make('config')->get('factur-x.mpdf_config'),
        ));

        $this->app->singleton(FacturXInvoiceGenerator::class, static fn ($app) => new FacturXInvoiceGenerator(
            $app->make(InvoiceHtmlRenderer::class),
            $app->make(InvoicePdfARenderer::class),
            $app->make(FacturXGenerator::class),
        ));
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'factur-x');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/factur-x.php' => config_path('factur-x.php'),
            ], 'factur-x-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/factur-x'),
            ], 'factur-x-views');
        }
    }
}
