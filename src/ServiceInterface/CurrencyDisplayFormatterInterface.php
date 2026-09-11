<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyAmountDTO;
use App\Currencing\DTO\CurrencyDisplayDTO;

interface CurrencyDisplayFormatterInterface
{
    public function format(CurrencyAmountDTO $moneyAmount, ?string $locale = null): CurrencyDisplayDTO;

    public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): CurrencyDisplayDTO;
}
