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

    public function testSupportsSignedIntegerBoundariesWithoutFloatOverflow(): void
    {
        $parser = new CurrencyDecimalParser();

        self::assertSame(PHP_INT_MAX, $parser->parseToMinorUnits((string) PHP_INT_MAX, 'JPY', 0, CurrencyRoundingMode::Reject));
        self::assertSame(PHP_INT_MIN, $parser->parseToMinorUnits((string) PHP_INT_MIN, 'JPY', 0, CurrencyRoundingMode::Reject));
        self::assertSame((string) PHP_INT_MIN, $parser->formatFromMinorUnits(PHP_INT_MIN, 0));
        self::assertSame('-92233720368547758.08', $parser->formatFromMinorUnits(PHP_INT_MIN, 2));
    }

    public function testRejectsAmountThatWouldOverflowMinorUnitInteger(): void
    {
        $parser = new CurrencyDecimalParser();

        $this->expectException(CurrencyInvalidAmountException::class);
        $this->expectExceptionMessage('exceeds the supported integer minor-unit range');
        $parser->parseToMinorUnits('92233720368547758.08', 'USD', 2, CurrencyRoundingMode::Reject);
    }

    public function testRoundingNearIntegerBoundaryRemainsDeterministic(): void
    {
        $parser = new CurrencyDecimalParser();

        self::assertSame(
            PHP_INT_MAX,
            $parser->parseToMinorUnits('9223372036854775.8074', 'USD', 3, CurrencyRoundingMode::Down),
        );

        $this->expectException(CurrencyInvalidAmountException::class);
        $parser->parseToMinorUnits('9223372036854775.8075', 'USD', 3, CurrencyRoundingMode::HalfUp);
    }

    public function testNormalizesFloatWhitespaceCommaAndExplicitPlusInputs(): void
    {
        $parser = new CurrencyDecimalParser();

        self::assertSame(1234, $parser->parseToMinorUnits(' 12,34 ', 'USD', 2, CurrencyRoundingMode::Reject));
        self::assertSame(1250, $parser->parseToMinorUnits(12.5, 'USD', 2, CurrencyRoundingMode::Reject));
        self::assertSame(1234, $parser->parseToMinorUnits('+12.34', 'USD', 2, CurrencyRoundingMode::Reject));
        self::assertSame(0, $parser->parseToMinorUnits('0.00', 'USD', 2, CurrencyRoundingMode::Reject));
    }

    public function testSupportsUpAndHalfUpRoundingWithoutIncrementWhenDiscardedDigitsAreZero(): void
    {
        $parser = new CurrencyDecimalParser();

        self::assertSame(124, $parser->parseToMinorUnits('1.231', 'USD', 2, CurrencyRoundingMode::Up));
        self::assertSame(123, $parser->parseToMinorUnits('1.230', 'USD', 2, CurrencyRoundingMode::Up));
        self::assertSame(123, $parser->parseToMinorUnits('1.234', 'USD', 2, CurrencyRoundingMode::HalfUp));
        self::assertSame(124, $parser->parseToMinorUnits('1.235', 'USD', 2, CurrencyRoundingMode::HalfUp));
        self::assertSame(13, $parser->parseToMinorUnits('12.5', 'JPY', 0, CurrencyRoundingMode::HalfUp));
    }

    public function testFormatsNegativeAndSubUnitMinorAmounts(): void
    {
        $parser = new CurrencyDecimalParser();

        self::assertSame('-0.05', $parser->formatFromMinorUnits(-5, 2));
        self::assertSame('0.05', $parser->formatFromMinorUnits(5, 2));
    }

    public function testRejectsInvalidAmountSyntax(): void
    {
        $parser = new CurrencyDecimalParser();

        $this->expectException(CurrencyInvalidAmountException::class);
        $parser->parseToMinorUnits('12.3.4', 'USD', 2, CurrencyRoundingMode::Reject);
    }

    public function testRejectsUnsupportedMinorUnitOnParse(): void
    {
        $parser = new CurrencyDecimalParser();

        $this->expectException(\InvalidArgumentException::class);
        $parser->parseToMinorUnits('1', 'USD', 9, CurrencyRoundingMode::Reject);
    }

    public function testRejectsUnsupportedMinorUnitOnFormat(): void
    {
        $parser = new CurrencyDecimalParser();

        $this->expectException(\InvalidArgumentException::class);
        $parser->formatFromMinorUnits(1, -1);
    }

    public function testRejectsNegativeOverflow(): void
    {
        $parser = new CurrencyDecimalParser();

        $this->expectException(CurrencyInvalidAmountException::class);
        $parser->parseToMinorUnits('-92233720368547758.09', 'USD', 2, CurrencyRoundingMode::Reject);
    }
}
