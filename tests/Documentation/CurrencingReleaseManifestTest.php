<?php

declare(strict_types=1);

namespace App\Tests\Documentation;

use PHPUnit\Framework\TestCase;

final class CurrencingReleaseManifestTest extends TestCase
{
    public function testReleaseManifestExists(): void
    {
        self::assertFileExists(__DIR__ . '/../../manifest.yaml');
        self::assertFileExists(__DIR__ . '/../../agent.md');
        self::assertFileExists(__DIR__ . '/../../docs/currencing/install.md');
        self::assertFileExists(__DIR__ . '/../../docs/currencing/inventory.md');
        self::assertFileExists(__DIR__ . '/../../docs/currencing/readiness.md');
    }

    public function testManifestContainsCanonicalDefaultNamespace(): void
    {
        $manifest = file_get_contents(__DIR__ . '/../../manifest.yaml');

        self::assertIsString($manifest);
        self::assertStringContainsString('App\\\\Currencing', $manifest);
        self::assertStringContainsString('currency_currency', $manifest);
        self::assertStringContainsString('Currencing must not depend on Exchanging', $manifest);
    }
}
