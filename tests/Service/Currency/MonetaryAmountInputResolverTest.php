<?php

declare(strict_types=1);

namespace App\Tests\Service\Currency;

use App\Dto\Currency\MoneyAmount;
use App\Dto\Currency\MoneyDisplay;
use App\Dto\Currency\MonetaryAmountInput;
use App\Dto\Currency\MoneyRoundingPolicy;
use App\Enum\Currency\MoneyRoundingMode;
use App\Service\Currency\MonetaryAmountInputResolver;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;
use App\ServiceInterface\Currency\MoneyAmountNormalizerInterface;
use App\ServiceInterface\Currency\MoneyDisplayFormatterInterface;
use App\ServiceInterface\Currency\MoneyRoundingPolicyResolverInterface;
use PHPUnit\Framework\TestCase;

final class MonetaryAmountInputResolverTest extends TestCase
{
    public function testResolvesNeighborInputWithoutDoctrineEntityLeakage(): void
    {
        $resolver = new MonetaryAmountInputResolver(
            new class implements MoneyAmountNormalizerInterface {
                public function normalize(string|int|float $amount, string $currencyCode, MoneyRoundingMode $roundingMode = MoneyRoundingMode::Reject): MoneyAmount
                {
                    self::assertSame('12.34', $amount);
                    self::assertSame('USD', strtoupper($currencyCode));
                    self::assertSame(MoneyRoundingMode::Reject, $roundingMode);

                    return new MoneyAmount(1234, $currencyCode);
                }
            },
            new class implements MoneyDisplayFormatterInterface {
                public function format(MoneyAmount $moneyAmount, ?string $locale = null): MoneyDisplay
                {
                    self::assertSame(1234, $moneyAmount->getMinorUnits());
                    self::assertSame('en_US', $locale);

                    return new MoneyDisplay('$12.34', 1234, 'USD', '12.34', $locale);
                }

                public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): MoneyDisplay
                {
                    return new MoneyDisplay('$12.34', $minorUnits, strtoupper($currencyCode), '12.34', $locale);
                }
            },
            new class implements CurrencyPrecisionResolverInterface {
                public function minorUnitFor(string $currencyCode): int
                {
                    self::assertSame('USD', strtoupper($currencyCode));

                    return 2;
                }
            },
            new class implements MoneyRoundingPolicyResolverInterface {
                public function resolveForInput(MonetaryAmountInput $input): MoneyRoundingPolicy
                {
                    return MoneyRoundingPolicy::fromInputMode($input->getRoundingMode());
                }

                public function resolveNamedPolicy(string $policyName): MoneyRoundingPolicy
                {
                    return new MoneyRoundingPolicy($policyName, MoneyRoundingMode::Reject);
                }

                public function resolveForContext(\App\Enum\Currency\MoneyRoundingContext $context): MoneyRoundingPolicy
                {
                    return new MoneyRoundingPolicy('canonical.reject', MoneyRoundingMode::Reject, $context);
                }
            },
        );

        $resolution = $resolver->resolve(new MonetaryAmountInput('12.34', 'usd', locale: 'en_US'));

        self::assertSame(1234, $resolution->getMinorUnits());
        self::assertSame('USD', $resolution->getCurrencyCode());
        self::assertSame(2, $resolution->getMinorUnit());
        self::assertSame('12.34', $resolution->getDecimalAmount());
        self::assertSame('$12.34', $resolution->getFormattedAmount());
        self::assertSame('input.reject', $resolution->getRoundingPolicy()?->getName());
    }
}
