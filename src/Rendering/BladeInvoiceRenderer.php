<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Rendering;

use Illuminate\Contracts\View\Factory as ViewFactory;
use PatODev\FacturX\Model\Invoice;

/**
 * Renders the configured Blade view (config('factur-x.view')) to HTML.
 * Publish the default view with `--tag=factur-x-views` to customize it.
 */
final class BladeInvoiceRenderer implements InvoiceHtmlRenderer
{
    public function __construct(
        private readonly ViewFactory $viewFactory,
        private readonly string $view,
    ) {
    }

    public function render(Invoice $invoice, ?string $view = null): string
    {
        return $this->viewFactory->make($view ?? $this->view, ['invoice' => $invoice])->render();
    }
}
