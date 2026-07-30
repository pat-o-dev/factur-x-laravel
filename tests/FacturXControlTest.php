<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Tests;

use DateTimeImmutable;
use PatODev\FacturX\Laravel\Facades\FacturX;
use PatODev\FacturX\Model\Invoice;

final class FacturXControlTest extends TestCase
{
    public function test_extracted_and_validated_xml_round_trips_and_passes(): void
    {
        $pdf = FacturX::render($this->sampleInvoice());

        $xml = FacturX::extractXml($pdf);
        $report = FacturX::validateInvoice($xml);

        self::assertStringContainsString('<rsm:CrossIndustryInvoice', $xml);
        self::assertTrue($report->passed(), (string) json_encode($report->failures()));
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
            ->line(itemName: 'Prestation de transport', quantity: 2.0, unitCode: 'C62', netUnitPrice: 100.0, vatRate: 20.0)
            ->build();
    }
}
