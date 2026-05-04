<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\MoneyAmount;
use App\Enum\Currency\MoneyRoundingMode;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;
use App\ServiceInterface\Currency\DecimalMoneyParserInterface;
use App\ServiceInterface\Currency\MoneyAmountNormalizerInterface;

final class CanonicalMoneyAmountNormalizer implements MoneyAmountNormalizerInterface
{
    public function __construct(
        private readonly CurrencyPrecisionResolverInterface $currencyPrecisionResolver,
        private readonly DecimalMoneyParserInterface $decimalMoneyParser,
    ) {
    }

    public function normalize(
        string|int|float $amount,
        string $currencyCode,
        MoneyRoundingMode $roundingMode = MoneyRoundingMode::Reject,
    ): MoneyAmount {
        $currencyCode = strtoupper($currencyCode);
        $minorUnit = $this->currencyPrecisionResolver->minorUnitFor($currencyCode);

        return new MoneyAmount(
            $this->decimalMoneyParser->parseToMinorUnits($amount, $currencyCode, $minorUnit, $roundingMode),
            $currencyCode,
        );
    }
}
