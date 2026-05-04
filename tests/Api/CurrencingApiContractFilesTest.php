<?php

declare(strict_types=1);

namespace App\Tests\Api;

use PHPUnit\Framework\TestCase;

final class CurrencingApiContractFilesTest extends TestCase
{
    public function testApiContractFilesExist(): void
    {
        self::assertFileExists(__DIR__ . '/../../docs/api/currencing.openapi.yaml');
        self::assertFileExists(__DIR__ . '/../../docs/api/currencing.http');
        self::assertFileExists(__DIR__ . '/../../delivery/release/currencing-endpoints.json');
    }

    public function testEndpointManifestContainsNormalizeRoute(): void
    {
        $contents = file_get_contents(__DIR__ . '/../../delivery/release/currencing-endpoints.json');

        self::assertIsString($contents);
        self::assertStringContainsString('/currencing/money/normalize', $contents);
        self::assertStringContainsString('currencing_money_normalize', $contents);
    }
}
