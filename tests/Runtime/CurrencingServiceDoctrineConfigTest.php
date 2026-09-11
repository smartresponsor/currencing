<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Runtime;

use PHPUnit\Framework\TestCase;

final class CurrencingServiceDoctrineConfigTest extends TestCase
{
    public function testExplicitServiceConfigExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/services/currencing.yaml');

        self::assertIsString($contents);
        self::assertStringContainsString('App\\Currencing\\Service\\', $contents);
        self::assertStringContainsString('CurrencyMetadataProviderInterface', $contents);
        self::assertStringContainsString('CurrencyRoundingPolicyResolverInterface', $contents);
    }

    public function testDoctrineMappingConfigExists(): void
    {
        $contents = file_get_contents(__DIR__.'/../../config/packages/doctrine_currencing.yaml');

        self::assertIsString($contents);
        self::assertStringContainsString('src/Entity/Currency', $contents);
        self::assertStringContainsString("prefix: 'App\\Currencing\\Entity\\Currency'", $contents);
    }
}
