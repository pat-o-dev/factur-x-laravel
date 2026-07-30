<?php

declare(strict_types=1);

namespace PatODev\FacturX\Laravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use PatODev\FacturX\Laravel\FacturXServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [FacturXServiceProvider::class];
    }
}
