<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel;

use PatODev\FacturX\FacturXGenerator;
use PatODev\FacturX\Laravel\Rendering\InvoiceHtmlRenderer;
use PatODev\FacturX\Laravel\Rendering\InvoicePdfARenderer;
use PatODev\FacturX\Model\Invoice;

/**
 * Full Invoice -> Factur-X hybrid PDF pipeline: renders the invoice template
 * to HTML, converts it to PDF/A, then hands it to the framework-agnostic
 * FacturXGenerator to attach the factur-x.xml + XMP metadata.
 */
final class FacturXInvoiceGenerator
{
    public function __construct(
        private readonly InvoiceHtmlRenderer $htmlRenderer,
        private readonly InvoicePdfARenderer $pdfRenderer,
        private readonly FacturXGenerator $core,
    ) {
    }

    public function generate(
        Invoice $invoice,
        float $prepaidAmount = 0.0,
        float $roundingAmount = 0.0,
    ): string {
        $html = $this->htmlRenderer->render($invoice);
        $basePdf = $this->pdfRenderer->render($html);

        return $this->core->generateHybridPdf($invoice, $basePdf, $prepaidAmount, $roundingAmount);
    }
}
