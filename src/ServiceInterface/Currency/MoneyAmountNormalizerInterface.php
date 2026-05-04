<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\MoneyAmount;
use App\Enum\Currency\MoneyRoundingMode;

interface MoneyAmountNormalizerInterface
{
    public function normalize(
        string|int|float $amount,
        string $currencyCode,
        MoneyRoundingMode $roundingMode = MoneyRoundingMode::Reject,
    ): MoneyAmount;
}
