<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Enum\Currency\CurrencyRoundingMode;

interface CurrencyDecimalParserInterface
{
    public function parseToMinorUnits(
        string|int|float $amount,
        string $currencyCode,
        int $minorUnit,
        CurrencyRoundingMode $roundingMode,
    ): int;

    public function formatFromMinorUnits(int $amountMinor, int $minorUnit): string;
}
