<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default XMP metadata
    |--------------------------------------------------------------------------
    |
    | Used when generating the hybrid PDF/A-3 unless overridden per invoice.
    |
    */

    'conformance_level' => env('FACTUR_X_CONFORMANCE_LEVEL', 'EN 16931'),

    'pdfa_conformance' => env('FACTUR_X_PDFA_CONFORMANCE', 'B'),

    /*
    |--------------------------------------------------------------------------
    | Invoice template rendering
    |--------------------------------------------------------------------------
    |
    | The Blade view used by the default InvoiceHtmlRenderer to produce the
    | visual invoice, unless a $view is passed to FacturX::render()/generate()
    | for a specific invoice. Publish it with `--tag=factur-x-views` to
    | customize it, or rebind PatODev\FacturX\Laravel\Rendering\InvoiceHtmlRenderer
    | in the container to use a different templating engine entirely.
    |
    */

    'view' => 'factur-x::invoice',

    /*
    |--------------------------------------------------------------------------
    | mPDF options
    |--------------------------------------------------------------------------
    |
    | PDF/A version used by the default InvoicePdfARenderer (MpdfInvoicePdfRenderer)
    | when converting the rendered HTML to the base PDF/A document. `mpdf_config`
    | is merged into the Mpdf constructor config (e.g. 'format', 'tempDir').
    |
    */

    'pdfa_version' => env('FACTUR_X_PDFA_VERSION', '3-B'),

    'mpdf_config' => [],

];
