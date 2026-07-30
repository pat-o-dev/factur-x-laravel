# pat-o-dev/factur-x-laravel

Laravel bridge for [`pat-o-dev/factur-x`](https://github.com/pat-o-dev/factur-x):
a `ServiceProvider`, a `FacturX` facade, a default invoice template, and a
full `Invoice` → rendered PDF → Factur-X hybrid PDF pipeline.

## Installation

```bash
composer require pat-o-dev/factur-x-laravel
```

## Usage

### Low-level: XML / hybridizing your own PDF

```php
use PatODev\FacturX\Laravel\Facades\FacturX;

// $invoice is a PatODev\FacturX\Model\Invoice (see pat-o-dev/factur-x README)
$xml = FacturX::generateXml($invoice);

$basePdf = /* bytes of a PDF/A invoice you already render, e.g. via mPDF */;
$hybridPdf = FacturX::generateHybridPdf($invoice, $basePdf);
```

### Full pipeline: render the invoice template and hybridize in one call

`FacturXInvoiceGenerator` renders a Blade view to HTML, converts it to PDF/A
via mPDF, then hybridizes it — no need to bring your own PDF renderer:

```php
use PatODev\FacturX\Laravel\FacturXInvoiceGenerator;

$hybridPdf = app(FacturXInvoiceGenerator::class)->generate($invoice);

return response($hybridPdf, 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'attachment; filename="'.$invoice->number.'.pdf"',
]);
```

### Customizing the invoice template

Publish the default Blade view and edit it directly:

```bash
php artisan vendor:publish --tag=factur-x-views
# -> resources/views/vendor/factur-x/invoice.blade.php
```

Or point `config('factur-x.view')` at your own view name entirely.

### Using a different rendering engine (Twig, pre-rendered React/Vue, ...)

`FacturXInvoiceGenerator` depends on the
`PatODev\FacturX\Laravel\Rendering\InvoiceHtmlRenderer` interface
(`render(Invoice $invoice): string`, returning HTML), bound by default to
`BladeInvoiceRenderer`. Rebind it in a service provider to swap engines:

```php
$this->app->bind(InvoiceHtmlRenderer::class, MyCustomRenderer::class);
```

Note: the HTML is converted to PDF/A by mPDF, which renders static HTML/CSS
only — it does not execute JavaScript. A React/Vue-based renderer must
server-side render to a plain HTML string before returning it (client-side
hydration won't run inside the PDF converter).

### mPDF / PDF/A options

`config('factur-x.pdfa_version')` (default `3-B`) and
`config('factur-x.mpdf_config')` (merged into the `Mpdf` constructor config,
e.g. `format`, `tempDir`) control the base PDF produced by the default
`InvoicePdfARenderer` (`MpdfInvoicePdfRenderer`). Swap that binding the same
way as `InvoiceHtmlRenderer` to use a different PDF/A engine (e.g. TCPDF).

## Publishing

```bash
php artisan vendor:publish --tag=factur-x-config  # config/factur-x.php
php artisan vendor:publish --tag=factur-x-views   # resources/views/vendor/factur-x/
```

## Testing

```bash
composer install
vendor/bin/phpunit
```
