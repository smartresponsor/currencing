<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\DTO\CurrencyAmountDTO;
use App\Currencing\DTO\CurrencyDisplayDTO;
use App\Currencing\ServiceInterface\CurrencyDisplayFormatterInterface;
use App\Currencing\ServiceInterface\CurrencyNormalizerInterface;

final class CurrencyDisplayFormatter implements CurrencyDisplayFormatterInterface
{
    public function __construct(private readonly CurrencyNormalizerInterface $moneyNormalizer)
    {
    }

    public function format(CurrencyAmountDTO $moneyAmount, ?string $locale = null): CurrencyDisplayDTO
    {
        return $this->formatMinorUnits(
            $moneyAmount->getMinorUnits(),
            $moneyAmount->getCurrencyCode(),
            $locale,
        );
    }

    public function formatMinorUnits(int $minorUnits, string $currencyCode, ?string $locale = null): CurrencyDisplayDTO
    {
        $currencyCode = strtoupper($currencyCode);
        $decimalAmount = $this->moneyNormalizer->minorUnitsToDecimalString($minorUnits, $currencyCode);

        $formatter = new \NumberFormatter($locale ?? 'en_US', \NumberFormatter::CURRENCY);
        $formatted = $formatter->formatCurrency((float) $decimalAmount, $currencyCode);

        return new CurrencyDisplayDTO($formatted, $minorUnits, $currencyCode, $decimalAmount, $locale);
    }
}
