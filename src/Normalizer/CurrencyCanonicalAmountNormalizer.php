<?php

declare(strict_types=1);

namespace App\Currencing\Normalizer;

use App\Currencing\DTO\CurrencyAmountDTO;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\ServiceInterface\CurrencyAmountNormalizerInterface;
use App\Currencing\ServiceInterface\CurrencyDecimalParserInterface;
use App\Currencing\ServiceInterface\CurrencyPrecisionResolverInterface;

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
