<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

interface MoneyNormalizerInterface
{
    public function normalizeToMinorUnits(string|int|float $amount, string $currencyCode): int;

    public function minorUnitsToDecimalString(int $amountMinor, string $currencyCode): string;
}
