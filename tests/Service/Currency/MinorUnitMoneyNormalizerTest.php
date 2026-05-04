<?php

declare(strict_types=1);

namespace App\Tests\Service\Currency;

use App\Exception\Currency\UnsupportedCurrencyCodeException;
use App\Service\Currency\CurrencyCodeValidator;
use App\Service\Currency\CurrencyPrecisionResolver;
use App\Service\Currency\DecimalMoneyParser;
use App\Service\Currency\MinorUnitMoneyNormalizer;
use App\ServiceInterface\Currency\CurrencyMetadataProviderInterface;
use PHPUnit\Framework\TestCase;

final class MinorUnitMoneyNormalizerTest extends TestCase
{
    public function testNormalizeDecimalAmountToMinorUnits(): void
    {
        $provider = new class implements CurrencyMetadataProviderInterface {
            public function metadataFor(string $code, ?string $locale = null): array
            {
                return ['code' => strtoupper($code), 'numericCode' => null, 'minorUnit' => 'JPY' === strtoupper($code) ? 0 : 2, 'symbol' => null, 'displayName' => null];
            }

            public function knownCodes(): array
            {
                return ['USD', 'JPY'];
            }
        };

        $precisionResolver = new CurrencyPrecisionResolver($provider, new CurrencyCodeValidator($provider));
        $normalizer = new MinorUnitMoneyNormalizer($precisionResolver, new DecimalMoneyParser());

        self::assertSame(1234, $normalizer->normalizeToMinorUnits('12.34', 'USD'));
        self::assertSame(12, $normalizer->normalizeToMinorUnits('12.34', 'JPY'));
        self::assertSame('12.34', $normalizer->minorUnitsToDecimalString(1234, 'USD'));
        self::assertSame('12', $normalizer->minorUnitsToDecimalString(12, 'JPY'));
    }

    public function testRejectsUnsupportedCurrency(): void
    {
        $provider = new class implements CurrencyMetadataProviderInterface {
            public function metadataFor(string $code, ?string $locale = null): array
            {
                return ['code' => strtoupper($code), 'numericCode' => null, 'minorUnit' => 2, 'symbol' => null, 'displayName' => null];
            }

            public function knownCodes(): array
            {
                return ['USD'];
            }
        };

        $precisionResolver = new CurrencyPrecisionResolver($provider, new CurrencyCodeValidator($provider));
        $normalizer = new MinorUnitMoneyNormalizer($precisionResolver, new DecimalMoneyParser());

        $this->expectException(UnsupportedCurrencyCodeException::class);
        $normalizer->normalizeToMinorUnits('10.00', 'EUR');
    }
}
