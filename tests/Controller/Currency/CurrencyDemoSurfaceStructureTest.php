<?php

declare(strict_types=1);

namespace App\Tests\Controller\Currency;

use PHPUnit\Framework\TestCase;

/**
 * Guards the M9 read/demo surface against accidental route/template removal.
 */
final class CurrencyDemoSurfaceStructureTest extends TestCase
{
    public function testDemoControllerRouteExists(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../../src/Controller/Currency/CurrencyDemoController.php');

        self::assertIsString($controller);
        self::assertStringContainsString('/currencing/demo', $controller);
        self::assertStringContainsString('currencing_demo_index', $controller);
    }

    public function testAdminPreviewControllerRouteExists(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../../src/Controller/Currency/CurrencyAdminPreviewController.php');

        self::assertIsString($controller);
        self::assertStringContainsString('/currencing/admin-preview/currencies', $controller);
        self::assertStringContainsString('currencing_admin_preview_currencies', $controller);
    }

    public function testTemplatesExist(): void
    {
        self::assertFileExists(__DIR__ . '/../../../src/Resources/views/layout.html.twig');
        self::assertFileExists(__DIR__ . '/../../../src/Resources/views/currency/demo/index.html.twig');
        self::assertFileExists(__DIR__ . '/../../../src/Resources/views/currency/admin-preview/currencies.html.twig');
    }
}
