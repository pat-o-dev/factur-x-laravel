# pat-o-dev/factur-x-laravel

Laravel bridge for [`pat-o-dev/factur-x`](../factur-x): a `ServiceProvider`,
a `FacturX` facade and (soon) an Artisan command to generate Factur-X
invoices from Laravel apps.

## Installation (while developing in-repo)

Both packages currently live under `packages/` in this monorepo and are
wired via Composer *path repositories* + `symlink: true`, so editing the
package source is immediately reflected in the app — see the root
`composer.json` `repositories` entry. Once extracted to their own GitHub
repos, this becomes a normal `composer require pat-o-dev/factur-x-laravel`.

## Usage

```php
use PatODev\FacturX\Laravel\Facades\FacturX;

// $invoice is a PatODev\FacturX\Model\Invoice (see pat-o-dev/factur-x README)
$xml = FacturX::generateXml($invoice);

$basePdf = /* bytes of a PDF/A invoice you already render, e.g. via mPDF */;
$hybridPdf = FacturX::generateHybridPdf($invoice, $basePdf);

return response($hybridPdf, 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'attachment; filename="'.$invoice->number.'.pdf"',
]);
```

Publish the config (mostly XMP defaults) with:

```bash
php artisan vendor:publish --tag=factur-x-config
```

## Testing

```bash
composer install
vendor/bin/phpunit
```
