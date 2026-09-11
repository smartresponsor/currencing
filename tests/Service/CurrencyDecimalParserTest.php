<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\Exception\CurrencyInvalidAmountException;
use App\Currencing\Service\CurrencyDecimalParser;
use PHPUnit\Framework\TestCase;

final class CurrencyDecimalParserTest extends TestCase
{
    public function testRejectsOverPreciseInputByDefault(): void
    {
        $parser = new CurrencyDecimalParser();

        $this->expectException(CurrencyInvalidAmountException::class);
        $parser->parseToMinorUnits('12.345', 'USD', 2, CurrencyRoundingMode::Reject);
    }

    public function testParsesAndFormatsMinorUnits(): void
    {
        $parser = new CurrencyDecimalParser();

        self::assertSame(1235, $parser->parseToMinorUnits('12.345', 'USD', 2, CurrencyRoundingMode::HalfUp));
        self::assertSame(12, $parser->parseToMinorUnits('12.34', 'JPY', 0, CurrencyRoundingMode::Down));
        self::assertSame('12.35', $parser->formatFromMinorUnits(1235, 2));
        self::assertSame('12', $parser->formatFromMinorUnits(12, 0));
    }
}
