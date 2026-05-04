<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Enum\Currency\MoneyRoundingMode;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;
use App\ServiceInterface\Currency\DecimalMoneyParserInterface;
use App\ServiceInterface\Currency\MoneyNormalizerInterface;

final class MinorUnitMoneyNormalizer implements MoneyNormalizerInterface
{
    public function __construct(
        private readonly CurrencyPrecisionResolverInterface $currencyPrecisionResolver,
        private readonly DecimalMoneyParserInterface $decimalMoneyParser,
    ) {
    }

    public function normalizeToMinorUnits(string|int|float $amount, string $currencyCode): int
    {
        $minorUnit = $this->currencyPrecisionResolver->minorUnitFor($currencyCode);

        return $this->decimalMoneyParser->parseToMinorUnits($amount, $currencyCode, $minorUnit, MoneyRoundingMode::HalfUp);
    }

    public function minorUnitsToDecimalString(int $amountMinor, string $currencyCode): string
    {
        return $this->decimalMoneyParser->formatFromMinorUnits(
            $amountMinor,
            $this->currencyPrecisionResolver->minorUnitFor($currencyCode),
        );
    }
}
