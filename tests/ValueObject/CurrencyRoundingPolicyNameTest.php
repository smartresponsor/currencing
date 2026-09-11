<?php

declare(strict_types=1);

namespace App\Currencing\Tests\ValueObject;

use App\Currencing\ValueObject\CurrencyRoundingPolicyName;
use PHPUnit\Framework\TestCase;

final class CurrencyRoundingPolicyNameTest extends TestCase
{
    public function testNormalizesPolicyName(): void
    {
        self::assertSame('taxating.reject', (new CurrencyRoundingPolicyName(' Taxating.Reject '))->value());
    }

    public function testRejectsInvalidPolicyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CurrencyRoundingPolicyName('bad policy nameEntity');
    }
}
