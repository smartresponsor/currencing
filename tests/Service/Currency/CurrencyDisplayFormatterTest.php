<?php

declare(strict_types=1);

namespace App\Tests\Service\Currency;

use App\Service\Currency\CurrencyDisplayFormatter;
use App\ServiceInterface\Currency\CurrencyNormalizerInterface;
use PHPUnit\Framework\TestCase;

final class CurrencyDisplayFormatterTest extends TestCase
{
    public function testBuildsTemplateSafeMoneyDisplay(): void
    {
        $formatter = new CurrencyDisplayFormatter(new class implements CurrencyNormalizerInterface {
            public function normalizeToMinorUnits(string|int|float $amount, string $currencyCode): int
            {
                return 1234;
            }

            public function minorUnitsToDecimalString(int $amountMinor, string $currencyCode): string
            {
                return '12.34';
            }
        });

        $display = $formatter->formatMinorUnits(1234, 'usd', 'en_US');

        self::assertSame(1234, $display->getMinorUnits());
        self::assertSame('USD', $display->getCurrencyCode());
        self::assertSame('12.34', $display->getDecimalAmount());
        self::assertNotSame('', $display->getFormatted());
    }
}
