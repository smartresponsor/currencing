<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

interface CurrencyFormatterInterface
{
    public function formatMinorUnits(int $amountMinor, string $currencyCode, ?string $locale = null): string;
}
