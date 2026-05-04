<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\ServiceInterface\Currency\CurrencyFormatterInterface;
use App\ServiceInterface\Currency\MoneyDisplayFormatterInterface;

final class IntlCurrencyFormatter implements CurrencyFormatterInterface
{
    public function __construct(private readonly MoneyDisplayFormatterInterface $moneyDisplayFormatter)
    {
    }

    public function formatMinorUnits(int $amountMinor, string $currencyCode, ?string $locale = null): string
    {
        return $this->moneyDisplayFormatter
            ->formatMinorUnits($amountMinor, $currencyCode, $locale)
            ->getFormatted();
    }
}
