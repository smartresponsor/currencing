<?php

declare(strict_types=1);

namespace App\Tests\ValueObject\Currency;

use App\ValueObject\Currency\MoneyRoundingPolicyName;
use PHPUnit\Framework\TestCase;

final class MoneyRoundingPolicyNameTest extends TestCase
{
    public function testNormalizesPolicyName(): void
    {
        self::assertSame('taxating.reject', (new MoneyRoundingPolicyName(' Taxating.Reject '))->value());
    }

    public function testRejectsInvalidPolicyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new MoneyRoundingPolicyName('bad policy name');
    }
}
