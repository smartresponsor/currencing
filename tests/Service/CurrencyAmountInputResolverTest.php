<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\DTO\CurrencyAmountDTO;
use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyDisplayDTO;
use App\Currencing\DTO\CurrencyRoundingPolicyDTO;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\Service\CurrencyAmountInputResolver;
use App\Currencing\ServiceInterface\CurrencyAmountNormalizerInterface;
use App\Currencing\ServiceInterface\CurrencyDisplayFormatterInterface;
use App\Currencing\ServiceInterface\CurrencyPrecisionResolverInterface;
use App\Currencing\ServiceInterface\CurrencyRoundingPolicyResolverInterface;
use PHPUnit\Framework\TestCase;

final class CurrencyAmountInputResolverTest extends TestCase
{
    public function testResolvesNeighborInputWithoutDoctrineEntityLeakage(): void
    {
        $resolver = new CurrencyAmountInputResolver(
            new class implements CurrencyAmountNormalizerInterface {
                public function normalize(string|int|float $amount, string $currencyCode, CurrencyRoundingMode $roundingMode = CurrencyRoundingMode::Reject): CurrencyAmountDTO
                {
                    TestCase::assertSame('12.34', $amount);
                    TestCase::assertSame('USD', strtoupper($currencyCode));
                    TestCase::assertSame(CurrencyRoundingMode::Reject, $roundingMode);

                    return new CurrencyAmountDTO(1234, $currencyCode);
                }
            },
            new class implements CurrencyDisplayFormatterInterface {
                public function format(CurrencyAmountDTO $moneyAmount, ?string $locale = null): CurrencyDisplayDTO
                {
                    TestCase::assertSame(1234, $moneyAmount->getMinorUnits());
                    TestCase::assertSame('en_US', $locale);

                    return new CurrencyDisplayDTO('$12.34', 1234, 'USD', '12.34', $locale);
                }

                public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): CurrencyDisplayDTO
                {
                    return new CurrencyDisplayDTO('$12.34', $minorUnits, strtoupper($currencyCode), '12.34', $locale);
                }
            },
            new class implements CurrencyPrecisionResolverInterface {
                public function minorUnitFor(string $currencyCode): int
                {
                    TestCase::assertSame('USD', strtoupper($currencyCode));

                    return 2;
                }

                public function factorFor(string $currencyCode): int
                {
                    return 10 ** $this->minorUnitFor($currencyCode);
                }
            },
            new class implements CurrencyRoundingPolicyResolverInterface {
                public function resolveForInput(CurrencyAmountInputDTO $input): CurrencyRoundingPolicyDTO
                {
                    return CurrencyRoundingPolicyDTO::fromInputMode($input->getRoundingMode());
                }

                public function resolveNamedPolicy(string $policyName): CurrencyRoundingPolicyDTO
                {
                    return new CurrencyRoundingPolicyDTO($policyName, CurrencyRoundingMode::Reject);
                }

                public function resolveForContext(\App\Currencing\Enum\CurrencyRoundingContext $context): CurrencyRoundingPolicyDTO
                {
                    return new CurrencyRoundingPolicyDTO('canonical.reject', CurrencyRoundingMode::Reject, $context);
                }
            },
        );

        $resolution = $resolver->resolve(new CurrencyAmountInputDTO('12.34', 'usd', locale: 'en_US'));

        TestCase::assertSame(1234, $resolution->getMinorUnits());
        TestCase::assertSame('USD', $resolution->getCurrencyCode());
        TestCase::assertSame(2, $resolution->getMinorUnit());
        TestCase::assertSame('12.34', $resolution->getDecimalAmount());
        TestCase::assertSame('$12.34', $resolution->getFormattedAmount());
        TestCase::assertSame('input.reject', $resolution->getRoundingPolicy()?->getName());
    }
}
