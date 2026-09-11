<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Runtime;

use PHPUnit\Framework\TestCase;

final class CurrencingServiceDoctrineConfigTest extends TestCase
{
    public function testExplicitServiceConfigExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/services/currency_services.yaml');

        self::assertIsString($contents);
        self::assertStringContainsString('App\\Currencing\\Service\\', $contents);
        self::assertStringContainsString('CurrencyMetadataProviderInterface', $contents);
        self::assertStringContainsString('CurrencyRoundingPolicyResolverInterface', $contents);
    }

    public function testDoctrineMappingConfigExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/packages/currency_doctrine.yaml');

        self::assertIsString($contents);
        self::assertStringContainsString('src/Entity/Currency', $contents);
        self::assertStringContainsString("prefix: 'App\\Currencing\\Entity\\Currency'", $contents);
    }
}
