<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Rendering;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Default InvoicePdfARenderer: renders HTML to PDF/A bytes via mPDF's
 * native PDF/A mode (embeds the sRGB output intent, subsets fonts).
 */
final class MpdfInvoicePdfRenderer implements InvoicePdfARenderer
{
    /**
     * @param  array<string, mixed>  $mpdfConfig  Extra options merged into the Mpdf constructor config,
     *                                             e.g. ['format' => 'A4', 'tempDir' => storage_path('app/mpdf')].
     */
    public function __construct(
        private readonly string $pdfaVersion = '3-B',
        private readonly array $mpdfConfig = [],
    ) {
    }

    public function render(string $html): string
    {
        $mpdf = new Mpdf(array_merge([
            'mode' => 'utf-8',
            'format' => 'A4',
        ], $this->mpdfConfig, [
            'PDFA' => true,
            'PDFAauto' => true,
            'PDFAversion' => $this->pdfaVersion,
        ]));

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}
