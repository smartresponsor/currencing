<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyAmountDTO;
use App\Enum\Currency\CurrencyRoundingMode;

interface CurrencyAmountNormalizerInterface
{
    public function normalize(
        string|int|float $amount,
        string $currencyCode,
        CurrencyRoundingMode $roundingMode = CurrencyRoundingMode::Reject,
    ): CurrencyAmountDTO;
}
