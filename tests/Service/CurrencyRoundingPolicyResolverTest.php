<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\Enum\CurrencyRoundingContext;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\Service\CurrencyRoundingPolicyResolver;
use PHPUnit\Framework\TestCase;

final class CurrencyRoundingPolicyResolverTest extends TestCase
{
    public function testUsesInputModeWhenNoPolicyOrContextIsProvided(): void
    {
        $resolver = new CurrencyRoundingPolicyResolver();
        $policy = $resolver->resolveForInput(new CurrencyAmountInputDTO('12.345', 'USD', CurrencyRoundingMode::Down));

        self::assertSame('input.down', $policy->getName());
        self::assertSame(CurrencyRoundingMode::Down, $policy->getRoundingMode());
    }

    public function testResolvesNamedFormattingPolicy(): void
    {
        $resolver = new CurrencyRoundingPolicyResolver();
        $policy = $resolver->resolveNamedPolicy('formatting.half_up');

        self::assertSame(CurrencyRoundingContext::Formatting, $policy->getContext());
        self::assertSame(CurrencyRoundingMode::HalfUp, $policy->getRoundingMode());
    }

    public function testInputNamedPolicyOverridesInputRoundingMode(): void
    {
        $resolver = new CurrencyRoundingPolicyResolver();
        $policy = $resolver->resolveForInput(new CurrencyAmountInputDTO(
            '12.345',
            'USD',
            CurrencyRoundingMode::Reject,
            roundingPolicyName: 'discounting.half_up',
        ));

        self::assertSame('discounting.half_up', $policy->getName());
        self::assertSame(CurrencyRoundingMode::HalfUp, $policy->getRoundingMode());
    }

    public function testContextPolicyCanBeSelectedWithoutNamingIt(): void
    {
        $resolver = new CurrencyRoundingPolicyResolver();
        $policy = $resolver->resolveForInput(new CurrencyAmountInputDTO(
            '12.345',
            'USD',
            roundingContext: CurrencyRoundingContext::Taxating,
        ));

        self::assertSame('taxating.reject', $policy->getName());
        self::assertSame(CurrencyRoundingMode::Reject, $policy->getRoundingMode());
    }
}
