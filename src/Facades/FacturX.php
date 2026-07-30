<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use PatODev\FacturX\Builder\InvoiceBuilder;
use PatODev\FacturX\Builder\PartyBuilder;
use PatODev\FacturX\FacturXGenerator;
use PatODev\FacturX\Laravel\FacturXInvoiceGenerator;
use PatODev\FacturX\Model\Invoice;
use PatODev\FacturX\Model\Party;

/**
 * @method static string generateXml(Invoice $invoice, float $prepaidAmount = 0.0, float $roundingAmount = 0.0)
 * @method static string generateHybridPdf(Invoice $invoice, string $basePdf, float $prepaidAmount = 0.0, float $roundingAmount = 0.0)
 *
 * @see FacturXGenerator
 */
class FacturX extends Facade
{
    /** Starts a fluent Invoice builder — shortcut for Invoice::builder(). */
    public static function invoice(string $number): InvoiceBuilder
    {
        return Invoice::builder($number);
    }

    /** Starts a fluent Party builder — shortcut for Party::builder(). */
    public static function party(string $name): PartyBuilder
    {
        return Party::builder($name);
    }

    /**
     * Full pipeline: renders the invoice template, converts it to PDF/A, and
     * hybridizes it — shortcut for app(FacturXInvoiceGenerator::class)->generate().
     */
    public static function render(Invoice $invoice, float $prepaidAmount = 0.0, float $roundingAmount = 0.0): string
    {
        return static::$app->make(FacturXInvoiceGenerator::class)->generate($invoice, $prepaidAmount, $roundingAmount);
    }

    protected static function getFacadeAccessor(): string
    {
        return FacturXGenerator::class;
    }
}
