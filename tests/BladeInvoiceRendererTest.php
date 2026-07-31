<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Tests;

use DateTimeImmutable;
use Illuminate\Support\Facades\View;
use PatODev\FacturX\Laravel\Facades\FacturX;
use PatODev\FacturX\Laravel\Rendering\InvoiceHtmlRenderer;
use PatODev\FacturX\Model\Invoice;

final class BladeInvoiceRendererTest extends TestCase
{
    public function test_it_renders_the_configured_default_view_when_no_override_is_given(): void
    {
        $html = $this->app->make(InvoiceHtmlRenderer::class)->render($this->sampleInvoice());

        self::assertStringContainsString('Facture F20260001', $html);
    }

    public function test_a_view_override_is_used_instead_of_the_configured_default(): void
    {
        View::addNamespace('test-fixtures', __DIR__.'/Fixtures/views');

        $html = $this->app->make(InvoiceHtmlRenderer::class)->render(
            $this->sampleInvoice(),
            'test-fixtures::minimal',
        );

        self::assertStringContainsString('MINIMAL TEST VIEW F20260001', $html);
    }

    private function sampleInvoice(): Invoice
    {
        $seller = FacturX::party('ACME Transport SARL')
            ->address('1 rue des Tests', 'Paris', '75001', 'FR')
            ->build();

        $buyer = FacturX::party('Client SAS')
            ->address('2 avenue du Test', 'Lyon', '69001', 'FR')
            ->build();

        return FacturX::invoice('F20260001')
            ->issueDate(new DateTimeImmutable('2026-01-15'))
            ->seller($seller)
            ->buyer($buyer)
            ->build();
    }
}
