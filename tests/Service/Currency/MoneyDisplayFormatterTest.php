<?php

declare(strict_types=1);

namespace App\Tests\Service\Currency;

use App\Service\Currency\MoneyDisplayFormatter;
use App\ServiceInterface\Currency\MoneyNormalizerInterface;
use PHPUnit\Framework\TestCase;

final class MoneyDisplayFormatterTest extends TestCase
{
    public function testBuildsTemplateSafeMoneyDisplay(): void
    {
        $formatter = new MoneyDisplayFormatter(new class implements MoneyNormalizerInterface {
            public function decimalStringToMinorUnits(string $amount, string $currencyCode): int
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
