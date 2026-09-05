<?php

declare(strict_types=1);

namespace App\Tests\ValueObject\Currency;

use App\ValueObject\Currency\CurrencyConsumerName;
use PHPUnit\Framework\TestCase;

final class CurrencyConsumerNameTest extends TestCase
{
    public function testAcceptsCanonicalConsumerName(): void
    {
        $nameEntity = new CurrencyConsumerName('Ordering');

        self::assertSame('Ordering', $nameEntity->value());
        self::assertSame('Ordering', (string) $nameEntity);
    }

    public function testRejectsEmptyConsumerName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CurrencyConsumerName('');
    }
}
