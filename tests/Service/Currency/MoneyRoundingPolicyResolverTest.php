<?php

declare(strict_types=1);

namespace App\Tests\Service\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Enum\Currency\MoneyRoundingContext;
use App\Enum\Currency\MoneyRoundingMode;
use App\Service\Currency\MoneyRoundingPolicyResolver;
use PHPUnit\Framework\TestCase;

final class MoneyRoundingPolicyResolverTest extends TestCase
{
    public function testUsesInputModeWhenNoPolicyOrContextIsProvided(): void
    {
        $resolver = new MoneyRoundingPolicyResolver();
        $policy = $resolver->resolveForInput(new MonetaryAmountInput('12.345', 'USD', MoneyRoundingMode::Down));

        self::assertSame('input.down', $policy->getName());
        self::assertSame(MoneyRoundingMode::Down, $policy->getRoundingMode());
    }

    public function testResolvesNamedFormattingPolicy(): void
    {
        $resolver = new MoneyRoundingPolicyResolver();
        $policy = $resolver->resolveNamedPolicy('formatting.half_up');

        self::assertSame(MoneyRoundingContext::Formatting, $policy->getContext());
        self::assertSame(MoneyRoundingMode::HalfUp, $policy->getRoundingMode());
    }

    public function testInputNamedPolicyOverridesInputRoundingMode(): void
    {
        $resolver = new MoneyRoundingPolicyResolver();
        $policy = $resolver->resolveForInput(new MonetaryAmountInput(
            '12.345',
            'USD',
            MoneyRoundingMode::Reject,
            roundingPolicyName: 'discounting.half_up',
        ));

        self::assertSame('discounting.half_up', $policy->getName());
        self::assertSame(MoneyRoundingMode::HalfUp, $policy->getRoundingMode());
    }

    public function testContextPolicyCanBeSelectedWithoutNamingIt(): void
    {
        $resolver = new MoneyRoundingPolicyResolver();
        $policy = $resolver->resolveForInput(new MonetaryAmountInput(
            '12.345',
            'USD',
            roundingContext: MoneyRoundingContext::Taxating,
        ));

        self::assertSame('taxating.reject', $policy->getName());
        self::assertSame(MoneyRoundingMode::Reject, $policy->getRoundingMode());
    }
}
