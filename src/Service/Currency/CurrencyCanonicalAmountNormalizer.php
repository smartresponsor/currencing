<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\CurrencyAmountDTO;
use App\Enum\Currency\CurrencyRoundingMode;
use App\ServiceInterface\Currency\CurrencyAmountNormalizerInterface;
use App\ServiceInterface\Currency\CurrencyDecimalParserInterface;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;

final class CurrencyCanonicalAmountNormalizer implements CurrencyAmountNormalizerInterface
{
    public function __construct(
        private readonly CurrencyPrecisionResolverInterface $currencyPrecisionResolver,
        private readonly CurrencyDecimalParserInterface $decimalMoneyParser,
    ) {
    }

    public function normalize(
        string|int|float $amount,
        string $currencyCode,
        CurrencyRoundingMode $roundingMode = CurrencyRoundingMode::Reject,
    ): CurrencyAmountDTO {
        $currencyCode = strtoupper($currencyCode);
        $minorUnit = $this->currencyPrecisionResolver->minorUnitFor($currencyCode);

        return new CurrencyAmountDTO(
            $this->decimalMoneyParser->parseToMinorUnits($amount, $currencyCode, $minorUnit, $roundingMode),
            $currencyCode,
        );
    }
}
