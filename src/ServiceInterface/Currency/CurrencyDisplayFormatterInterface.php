<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyAmountDTO;
use App\Dto\Currency\CurrencyDisplayDTO;

interface CurrencyDisplayFormatterInterface
{
    public function format(CurrencyAmountDTO $moneyAmount, ?string $locale = null): CurrencyDisplayDTO;

    public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): CurrencyDisplayDTO;
}
