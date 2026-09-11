<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\Enum\CurrencyRoundingMode;

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
