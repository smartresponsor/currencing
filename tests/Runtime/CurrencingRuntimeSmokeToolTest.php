<?php

declare(strict_types=1);

namespace App\Tests\Runtime;

use PHPUnit\Framework\TestCase;

final class CurrencingRuntimeSmokeToolTest extends TestCase
{
    public function testRuntimeSmokeToolExists(): void
    {
        self::assertFileExists(__DIR__ . '/../../tools/currencing-runtime-smoke-check.php');
    }

    public function testRuntimeSmokeToolReferencesCriticalServicesAndRoutes(): void
    {
        $contents = file_get_contents(__DIR__ . '/../../tools/currencing-runtime-smoke-check.php');

        self::assertIsString($contents);
        self::assertStringContainsString('CurrencyMetadataProviderInterface', $contents);
        self::assertStringContainsString('MonetaryAmountInputResolverInterface', $contents);
        self::assertStringContainsString('currencing_money_normalize', $contents);
        self::assertStringContainsString('currencing_conversion_boundary', $contents);
    }
}
