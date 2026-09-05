<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Enum\Currency\CurrencyRoundingMode;
use App\ServiceInterface\Currency\CurrencyDecimalParserInterface;
use App\ServiceInterface\Currency\CurrencyNormalizerInterface;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;

final class CurrencyMinorUnitNormalizer implements CurrencyNormalizerInterface
{
    public function __construct(
        private readonly CurrencyPrecisionResolverInterface $currencyPrecisionResolver,
        private readonly CurrencyDecimalParserInterface $decimalMoneyParser,
    ) {
    }

    public function normalizeToMinorUnits(string|int|float $amount, string $currencyCode): int
    {
        $minorUnit = $this->currencyPrecisionResolver->minorUnitFor($currencyCode);

        return $this->decimalMoneyParser->parseToMinorUnits($amount, $currencyCode, $minorUnit, CurrencyRoundingMode::HalfUp);
    }

    public function minorUnitsToDecimalString(int $amountMinor, string $currencyCode): string
    {
        return $this->decimalMoneyParser->formatFromMinorUnits(
            $amountMinor,
            $this->currencyPrecisionResolver->minorUnitFor($currencyCode),
        );
    }
}
