<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Rendering;

/**
 * Converts a static HTML string into PDF/A bytes, suitable as the
 * `$basePdf` argument of PatODev\FacturX\FacturXGenerator::generateHybridPdf().
 */
interface InvoicePdfARenderer
{
    public function render(string $html): string;
}
