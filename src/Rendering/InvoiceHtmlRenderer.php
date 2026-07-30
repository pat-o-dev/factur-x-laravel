<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Rendering;

use PatODev\FacturX\Model\Invoice;

/**
 * Renders an Invoice to a static HTML string (no JS execution downstream —
 * see MpdfInvoicePdfRenderer). Rebind this in the container to swap Blade
 * for another engine, or for HTML pre-rendered server-side from React/Vue.
 */
interface InvoiceHtmlRenderer
{
    public function render(Invoice $invoice): string;
}
