<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyAmountDTO;
use App\Currencing\Enum\CurrencyRoundingMode;

interface CurrencyAmountNormalizerInterface
{
    public function normalize(
        string|int|float $amount,
        string $currencyCode,
        CurrencyRoundingMode $roundingMode = CurrencyRoundingMode::Reject,
    ): CurrencyAmountDTO;
}
