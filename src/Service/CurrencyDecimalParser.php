<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\Exception\CurrencyInvalidAmountException;
use App\Currencing\ServiceInterface\CurrencyDecimalParserInterface;

final class CurrencyDecimalParser implements CurrencyDecimalParserInterface
{
    public function parseToMinorUnits(
        string|int|float $amount,
        string $currencyCode,
        int $minorUnit,
        CurrencyRoundingMode $roundingMode,
    ): int {
        $decimal = $this->normalizeInput($amount);
        $negative = str_starts_with($decimal, '-');
        $unsigned = ltrim($decimal, '+-');
        [$whole, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');

        if (strlen($fraction) > $minorUnit) {
            if (CurrencyRoundingMode::Reject === $roundingMode) {
                throw CurrencyInvalidAmountException::tooPrecise($decimal, $currencyCode, $minorUnit);
            }

            $unsigned = $this->roundUnsignedDecimal($whole, $fraction, $minorUnit, $roundingMode);
            [$whole, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');
        }

        $fraction = str_pad(substr($fraction, 0, $minorUnit), $minorUnit, '0');
        $minor = ((int) $whole) * (10 ** $minorUnit) + ('' === $fraction ? 0 : (int) $fraction);

        return $negative ? -$minor : $minor;
    }

    public function formatFromMinorUnits(int $amountMinor, int $minorUnit): string
    {
        $negative = $amountMinor < 0;
        $absolute = abs($amountMinor);
        $factor = 10 ** $minorUnit;
        $whole = intdiv($absolute, $factor);
        $fraction = $absolute % $factor;

        if (0 === $minorUnit) {
            return ($negative ? '-' : '').(string) $whole;
        }

        return sprintf('%s%d.%s', $negative ? '-' : '', $whole, str_pad((string) $fraction, $minorUnit, '0', STR_PAD_LEFT));
    }

    private function normalizeInput(string|int|float $amount): string
    {
        if (is_float($amount)) {
            $amount = rtrim(rtrim(sprintf('%.8F', $amount), '0'), '.');
        }

        $amount = trim((string) $amount);
        $amount = str_replace([' ', ','], ['', '.'], $amount);

        if (!preg_match('/^[+-]?\d+(?:\.\d+)?$/', $amount)) {
            throw CurrencyInvalidAmountException::forAmount($amount);
        }

        return $amount;
    }

    private function roundUnsignedDecimal(string $whole, string $fraction, int $minorUnit, CurrencyRoundingMode $roundingMode): string
    {
        $kept = substr($fraction, 0, $minorUnit);
        $discarded = substr($fraction, $minorUnit);
        $increment = match ($roundingMode) {
            CurrencyRoundingMode::Down => false,
            CurrencyRoundingMode::Up => '' !== trim($discarded, '0'),
            CurrencyRoundingMode::HalfUp => '' !== $discarded && ((int) $discarded[0]) >= 5,
            CurrencyRoundingMode::Reject => false,
        };

        $minor = ((int) $whole) * (10 ** $minorUnit) + ('' === $kept ? 0 : (int) str_pad($kept, $minorUnit, '0'));
        if ($increment) {
            ++$minor;
        }

        if (0 === $minorUnit) {
            return (string) $minor;
        }

        $factor = 10 ** $minorUnit;

        return intdiv($minor, $factor).'.'.str_pad((string) ($minor % $factor), $minorUnit, '0', STR_PAD_LEFT);
    }
}
