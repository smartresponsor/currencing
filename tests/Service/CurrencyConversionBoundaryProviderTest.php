<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\Service\CurrencyConversionBoundaryProvider;
use PHPUnit\Framework\TestCase;

final class CurrencyConversionBoundaryProviderTest extends TestCase
{
    public function testItProvidesDependencyDirection(): void
    {
        $boundary = (new CurrencyConversionBoundaryProvider())->provideBoundary();

        self::assertStringContainsString('Exchanging may depend on Currencing', $boundary->dependencyDirection);
        self::assertContains('money amount normalization', $boundary->currencingResponsibilities);
        self::assertContains('exchange-rate sourcing', $boundary->exchangingResponsibilities);
        self::assertContains('live exchange-rate fetching', $boundary->forbiddenCurrencingResponsibilities);
    }
}
