<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service\Http\Currency;

use PHPUnit\Framework\TestCase;

/**
 * Guards the M9 read/demo surface against accidental route/template removal.
 */
final class CurrencyDemoSurfaceStructureTest extends TestCase
{
    public function testDemoRouteExists(): void
    {
        $routes = file_get_contents(dirname(__DIR__, 4).'/config/routes/currency_routes.yaml');

        self::assertIsString($routes);
        self::assertStringContainsString('/currencing/demo', $routes);
        self::assertStringContainsString('currencing_demo_index', $routes);
    }

    public function testAdminPreviewRouteExists(): void
    {
        $routes = file_get_contents(dirname(__DIR__, 4).'/config/routes/currency_routes.yaml');

        self::assertIsString($routes);
        self::assertStringContainsString('/currencing/admin/preview/currencies', $routes);
        self::assertStringContainsString('currencing_admin_preview_currencies', $routes);
    }

    public function testTemplatesExist(): void
    {
        $root = dirname(__DIR__, 4);

        self::assertFileExists($root.'/src/Resources/views/layout.html.twig');
        self::assertFileExists($root.'/src/Resources/views/currency/demo/index.html.twig');
        self::assertFileExists($root.'/src/Resources/views/currency/admin-preview/currencies.html.twig');
    }
}
