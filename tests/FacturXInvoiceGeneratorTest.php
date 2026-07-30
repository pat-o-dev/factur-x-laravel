<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Tests;

use DateTimeImmutable;
use PatODev\FacturX\Enum\InvoiceTypeCode;
use PatODev\FacturX\Enum\VatCategory;
use PatODev\FacturX\Laravel\FacturXInvoiceGenerator;
use PatODev\FacturX\Model\Address;
use PatODev\FacturX\Model\Invoice;
use PatODev\FacturX\Model\InvoiceLine;
use PatODev\FacturX\Model\Party;

final class FacturXInvoiceGeneratorTest extends TestCase
{
    public function test_it_renders_the_default_template_into_a_hybrid_factur_x_pdf(): void
    {
        $invoice = new Invoice(
            number: 'F20260001',
            issueDate: new DateTimeImmutable('2026-01-15'),
            typeCode: InvoiceTypeCode::CommercialInvoice,
            seller: new Party(
                name: 'ACME Transport SARL',
                address: new Address(line1: '1 rue des Tests', city: 'Paris', postalCode: '75001', countryCode: 'FR'),
                legalRegistrationId: '123456789',
                vatNumber: 'FR12123456789',
            ),
            buyer: new Party(
                name: 'Client SAS',
                address: new Address(line1: '2 avenue du Test', city: 'Lyon', postalCode: '69001', countryCode: 'FR'),
                legalRegistrationId: '987654321',
            ),
        );

        $invoice->addLine(new InvoiceLine(
            lineId: '1',
            itemName: 'Prestation de transport',
            quantity: 2.0,
            unitCode: 'C62',
            netUnitPrice: 100.0,
            vatCategory: VatCategory::Standard,
            vatRate: 20.0,
        ));

        $pdf = $this->app->make(FacturXInvoiceGenerator::class)->generate($invoice);

        self::assertStringStartsWith('%PDF-', $pdf);
        self::assertStringContainsString('factur-x.xml', $pdf);
    }
}
