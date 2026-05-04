<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Enum\Currency\MoneyRoundingMode;

interface DecimalMoneyParserInterface
{
    public function parseToMinorUnits(
        string|int|float $amount,
        string $currencyCode,
        int $minorUnit,
        MoneyRoundingMode $roundingMode,
    ): int;

    public function formatFromMinorUnits(int $amountMinor, int $minorUnit): string;
}
