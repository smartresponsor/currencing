<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\MoneyAmount;
use App\Dto\Currency\MoneyDisplay;

interface MoneyDisplayFormatterInterface
{
    public function format(MoneyAmount $moneyAmount, ?string $locale = null): MoneyDisplay;

    public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): MoneyDisplay;
}
