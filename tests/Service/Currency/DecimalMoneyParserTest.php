<?php

declare(strict_types=1);

namespace App\Tests\Service\Currency;

use App\Enum\Currency\MoneyRoundingMode;
use App\Exception\Currency\InvalidMoneyAmountException;
use App\Service\Currency\DecimalMoneyParser;
use PHPUnit\Framework\TestCase;

final class DecimalMoneyParserTest extends TestCase
{
    public function testRejectsOverPreciseInputByDefault(): void
    {
        $parser = new DecimalMoneyParser();

        $this->expectException(InvalidMoneyAmountException::class);
        $parser->parseToMinorUnits('12.345', 'USD', 2, MoneyRoundingMode::Reject);
    }

    public function testParsesAndFormatsMinorUnits(): void
    {
        $parser = new DecimalMoneyParser();

        self::assertSame(1235, $parser->parseToMinorUnits('12.345', 'USD', 2, MoneyRoundingMode::HalfUp));
        self::assertSame(12, $parser->parseToMinorUnits('12.34', 'JPY', 0, MoneyRoundingMode::Down));
        self::assertSame('12.35', $parser->formatFromMinorUnits(1235, 2));
        self::assertSame('12', $parser->formatFromMinorUnits(12, 0));
    }
}
