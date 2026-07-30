<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Tests;

use PatODev\FacturX\FacturXGenerator;
use PatODev\FacturX\Laravel\Facades\FacturX;

final class FacturXServiceProviderTest extends TestCase
{
    public function test_it_binds_the_generator_as_a_singleton(): void
    {
        $first = $this->app->make(FacturXGenerator::class);
        $second = $this->app->make(FacturXGenerator::class);

        self::assertSame($first, $second);
    }

    public function test_it_merges_the_default_config(): void
    {
        self::assertSame('EN 16931', config('factur-x.conformance_level'));
        self::assertSame('B', config('factur-x.pdfa_conformance'));
    }

    public function test_the_facade_resolves_to_the_generator(): void
    {
        self::assertInstanceOf(FacturXGenerator::class, FacturX::getFacadeRoot());
    }
}
