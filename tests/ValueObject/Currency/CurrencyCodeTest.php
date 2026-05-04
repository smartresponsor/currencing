<?php

declare(strict_types=1);

namespace App\Tests\ValueObject\Currency;

use App\ValueObject\Currency\CurrencyCode;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CurrencyCodeTest extends TestCase
{
    public function testItNormalizesCode(): void
    {
        self::assertSame('USD', CurrencyCode::fromString(' usd ')->value());
    }

    public function testItRejectsInvalidCode(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CurrencyCode::fromString('US');
    }
}
