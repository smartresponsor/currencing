<?php

declare(strict_types=1);

namespace App\Currencing\Tests\ValueObject;

use App\Currencing\ValueObject\CurrencyConsumerName;
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
