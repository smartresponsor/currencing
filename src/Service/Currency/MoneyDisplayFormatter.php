<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\MoneyAmount;
use App\Dto\Currency\MoneyDisplay;
use App\ServiceInterface\Currency\MoneyDisplayFormatterInterface;
use App\ServiceInterface\Currency\MoneyNormalizerInterface;
use NumberFormatter;

final class MoneyDisplayFormatter implements MoneyDisplayFormatterInterface
{
    public function __construct(private readonly MoneyNormalizerInterface $moneyNormalizer)
    {
    }

    public function format(MoneyAmount $moneyAmount, ?string $locale = null): MoneyDisplay
    {
        return $this->formatMinorUnits(
            $moneyAmount->getMinorUnits(),
            $moneyAmount->getCurrencyCode(),
            $locale,
        );
    }

    public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): MoneyDisplay
    {
        $currencyCode = strtoupper($currencyCode);
        $decimalAmount = $this->moneyNormalizer->minorUnitsToDecimalString($minorUnits, $currencyCode);

        $formatter = new NumberFormatter($locale ?? 'en_US', NumberFormatter::CURRENCY);
        $formatted = $formatter->formatCurrency((float) $decimalAmount, $currencyCode);

        if (false === $formatted) {
            $formatted = $decimalAmount . ' ' . $currencyCode;
        }

        return new MoneyDisplay($formatted, $minorUnits, $currencyCode, $decimalAmount, $locale);
    }
}
