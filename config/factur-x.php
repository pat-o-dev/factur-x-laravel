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

];
