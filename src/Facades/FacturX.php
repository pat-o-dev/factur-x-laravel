<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use PatODev\FacturX\FacturXGenerator;

/**
 * @method static string generateXml(\PatODev\FacturX\Model\Invoice $invoice, float $prepaidAmount = 0.0, float $roundingAmount = 0.0)
 * @method static string generateHybridPdf(\PatODev\FacturX\Model\Invoice $invoice, string $basePdf, float $prepaidAmount = 0.0, float $roundingAmount = 0.0)
 *
 * @see FacturXGenerator
 */
class FacturX extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FacturXGenerator::class;
    }
}
