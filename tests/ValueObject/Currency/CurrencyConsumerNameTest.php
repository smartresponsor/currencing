<?php

declare(strict_types=1);

namespace App\Tests\ValueObject\Currency;

use App\ValueObject\Currency\CurrencyConsumerName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CurrencyConsumerNameTest extends TestCase
{
    public function testAcceptsCanonicalConsumerName(): void
    {
        $name = new CurrencyConsumerName('Ordering');

        self::assertSame('Ordering', $name->value());
        self::assertSame('Ordering', (string) $name);
    }

    public function testRejectsEmptyConsumerName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CurrencyConsumerName('');
    }
}
