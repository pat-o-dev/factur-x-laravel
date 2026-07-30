<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Tests;

use DateTimeImmutable;
use PatODev\FacturX\Enum\UnitOfMeasureCode;
use PatODev\FacturX\Laravel\Facades\FacturX;
use PatODev\FacturX\Laravel\FacturXInvoiceGenerator;
use PatODev\FacturX\Model\Invoice;

final class FacturXInvoiceGeneratorTest extends TestCase
{
    public function test_it_renders_the_default_template_into_a_hybrid_factur_x_pdf(): void
    {
        $invoice = $this->sampleInvoice();

        $pdf = $this->app->make(FacturXInvoiceGenerator::class)->generate($invoice);

        self::assertStringStartsWith('%PDF-', $pdf);
        self::assertStringContainsString('factur-x.xml', $pdf);
    }

    public function test_the_facade_render_shortcut_produces_the_same_pipeline(): void
    {
        $pdf = FacturX::render($this->sampleInvoice());

        self::assertStringStartsWith('%PDF-', $pdf);
        self::assertStringContainsString('factur-x.xml', $pdf);
    }

    private function sampleInvoice(): Invoice
    {
        $seller = FacturX::party('ACME Transport SARL')
            ->address('1 rue des Tests', 'Paris', '75001', 'FR')
            ->legalRegistrationId('123456789')
            ->vatNumber('FR12123456789')
            ->build();

        $buyer = FacturX::party('Client SAS')
            ->address('2 avenue du Test', 'Lyon', '69001', 'FR')
            ->legalRegistrationId('987654321')
            ->build();

        return FacturX::invoice('F20260001')
            ->issueDate(new DateTimeImmutable('2026-01-15'))
            ->seller($seller)
            ->buyer($buyer)
            ->dueInDays(30)
            ->line(itemName: 'Prestation de transport', quantity: 2.0, unitCode: UnitOfMeasureCode::Piece, netUnitPrice: 100.0, vatRate: 20.0)
            ->build();
    }
}
