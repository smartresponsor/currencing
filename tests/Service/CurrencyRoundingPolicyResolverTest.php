<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyChoiceDTO;
use App\Currencing\DTO\CurrencyRoundingPolicyDTO;
use App\Currencing\Enum\CurrencyRoundingContext;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\Resolver\CurrencyRoundingPolicyResolver;
use App\Currencing\ValueObject\CurrencyRoundingPolicyName;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('contextPolicyProvider')]
    public function testResolvesEveryCanonicalContext(CurrencyRoundingContext $context, string $expectedName, CurrencyRoundingMode $expectedMode): void
    {
        $policy = (new CurrencyRoundingPolicyResolver())->resolveForContext($context);

        self::assertSame($expectedName, $policy->getName());
        self::assertSame($context, $policy->getContext());
        self::assertSame($expectedMode, $policy->getRoundingMode());
    }

    /** @return iterable<string, array{CurrencyRoundingContext, string, CurrencyRoundingMode}> */
    public static function contextPolicyProvider(): iterable
    {
        yield 'canonical' => [CurrencyRoundingContext::Canonical, 'canonical.reject', CurrencyRoundingMode::Reject];
        yield 'ordering' => [CurrencyRoundingContext::Ordering, 'ordering.reject', CurrencyRoundingMode::Reject];
        yield 'paying' => [CurrencyRoundingContext::Paying, 'paying.reject', CurrencyRoundingMode::Reject];
        yield 'taxating' => [CurrencyRoundingContext::Taxating, 'taxating.reject', CurrencyRoundingMode::Reject];
        yield 'discounting' => [CurrencyRoundingContext::Discounting, 'discounting.half_up', CurrencyRoundingMode::HalfUp];
        yield 'subscription' => [CurrencyRoundingContext::Subscription, 'subscription.reject', CurrencyRoundingMode::Reject];
        yield 'formatting' => [CurrencyRoundingContext::Formatting, 'formatting.half_up', CurrencyRoundingMode::HalfUp];
        yield 'cash' => [CurrencyRoundingContext::Cash, 'cash.down', CurrencyRoundingMode::Down];
    }

    public function testRejectsUnknownNamedPolicy(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown money rounding policy');

        (new CurrencyRoundingPolicyResolver())->resolveNamedPolicy('unknown.reject');
    }

    public function testChoiceDtoExposesNormalizedPresentationContract(): void
    {
        $choice = new CurrencyChoiceDTO('usd', 'US Dollar', '$', 2);

        self::assertSame('USD', $choice->getCode());
        self::assertSame('US Dollar', $choice->getLabel());
        self::assertSame('$', $choice->getSymbol());
        self::assertSame(2, $choice->getMinorUnit());
        self::assertSame([
            'code' => 'USD',
            'label' => 'US Dollar',
            'symbol' => '$',
            'minorUnit' => 2,
        ], $choice->toArray());
    }

    public function testRoundingPolicyDtoExposesCompletePolicyContract(): void
    {
        $policy = new CurrencyRoundingPolicyDTO(
            'cash.down',
            CurrencyRoundingMode::Down,
            CurrencyRoundingContext::Cash,
            5,
            'Cash rounding.',
        );

        self::assertSame('cash.down', $policy->getName());
        self::assertSame(CurrencyRoundingMode::Down, $policy->getRoundingMode());
        self::assertSame(CurrencyRoundingContext::Cash, $policy->getContext());
        self::assertSame(5, $policy->getCashIncrementMinorUnits());
        self::assertSame('Cash rounding.', $policy->getDescription());

        $fromInput = CurrencyRoundingPolicyDTO::fromInputMode(CurrencyRoundingMode::HalfUp);
        self::assertSame('input.half_up', $fromInput->getName());
        self::assertSame(CurrencyRoundingContext::Canonical, $fromInput->getContext());
    }

    public function testRoundingPolicyDtoRejectsInvalidContractValues(): void
    {
        try {
            new CurrencyRoundingPolicyDTO('?', CurrencyRoundingMode::Reject);
            self::fail('Invalid policy identity must be rejected.');
        } catch (\InvalidArgumentException $exception) {
            self::assertStringContainsString('Invalid money rounding policy', $exception->getMessage());
        }

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cash increment minor units must be positive');

        new CurrencyRoundingPolicyDTO('cash.down', CurrencyRoundingMode::Down, CurrencyRoundingContext::Cash, 0);
    }

    public function testPolicyNameCanonicalizesAndComparesValueSemantics(): void
    {
        $canonical = new CurrencyRoundingPolicyName('  FORMATTING.HALF_UP  ');
        $same = new CurrencyRoundingPolicyName('formatting.half_up');
        $different = new CurrencyRoundingPolicyName('canonical.reject');

        self::assertSame('formatting.half_up', $canonical->value());
        self::assertSame('formatting.half_up', (string) $canonical);
        self::assertTrue($canonical->equals($same));
        self::assertFalse($canonical->equals($different));
    }

    public function testPolicyNameRejectsInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid money rounding policy');

        new CurrencyRoundingPolicyName('!');
    }
}
