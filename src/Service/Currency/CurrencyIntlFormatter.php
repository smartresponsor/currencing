<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\ServiceInterface\Currency\CurrencyDisplayFormatterInterface;
use App\ServiceInterface\Currency\CurrencyFormatterInterface;

final class CurrencyIntlFormatter implements CurrencyFormatterInterface
{
    public function __construct(private readonly CurrencyDisplayFormatterInterface $moneyDisplayFormatter)
    {
    }

    public function formatMinorUnits(int $amountMinor, string $currencyCode, ?string $locale = null): string
    {
        return $this->moneyDisplayFormatter
            ->formatMinorUnits($amountMinor, $currencyCode, $locale)
            ->getFormatted();
    }
}
