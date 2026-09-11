<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\Exception\CurrencyUnsupportedCodeException;
use App\Currencing\Service\CurrencyCodeValidator;
use App\Currencing\Service\CurrencyDecimalParser;
use App\Currencing\Service\CurrencyMinorUnitNormalizer;
use App\Currencing\Service\CurrencyPrecisionResolver;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;
use PHPUnit\Framework\TestCase;

final class CurrencyMinorUnitNormalizerTest extends TestCase
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
        $normalizer = new CurrencyMinorUnitNormalizer($precisionResolver, new CurrencyDecimalParser());

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
        $normalizer = new CurrencyMinorUnitNormalizer($precisionResolver, new CurrencyDecimalParser());

        $this->expectException(CurrencyUnsupportedCodeException::class);
        $normalizer->normalizeToMinorUnits('10.00', 'EUR');
    }
}
