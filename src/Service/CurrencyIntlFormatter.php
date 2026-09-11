<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\ServiceInterface\CurrencyDisplayFormatterInterface;
use App\Currencing\ServiceInterface\CurrencyFormatterInterface;

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
