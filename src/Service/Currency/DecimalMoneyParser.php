<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Enum\Currency\MoneyRoundingMode;
use App\Exception\Currency\InvalidMoneyAmountException;
use App\ServiceInterface\Currency\DecimalMoneyParserInterface;

final class DecimalMoneyParser implements DecimalMoneyParserInterface
{
    public function parseToMinorUnits(
        string|int|float $amount,
        string $currencyCode,
        int $minorUnit,
        MoneyRoundingMode $roundingMode,
    ): int {
        $decimal = $this->normalizeInput($amount);
        $negative = str_starts_with($decimal, '-');
        $unsigned = ltrim($decimal, '+-');
        [$whole, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');

        if (strlen($fraction) > $minorUnit) {
            if (MoneyRoundingMode::Reject === $roundingMode) {
                throw InvalidMoneyAmountException::tooPrecise($decimal, $currencyCode, $minorUnit);
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
            return ($negative ? '-' : '') . (string) $whole;
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
            throw InvalidMoneyAmountException::forAmount($amount);
        }

        return $amount;
    }

    private function roundUnsignedDecimal(string $whole, string $fraction, int $minorUnit, MoneyRoundingMode $roundingMode): string
    {
        $kept = substr($fraction, 0, $minorUnit);
        $discarded = substr($fraction, $minorUnit);
        $increment = match ($roundingMode) {
            MoneyRoundingMode::Down => false,
            MoneyRoundingMode::Up => '' !== trim($discarded, '0'),
            MoneyRoundingMode::HalfUp => '' !== $discarded && ((int) $discarded[0]) >= 5,
            MoneyRoundingMode::Reject => false,
        };

        $minor = ((int) $whole) * (10 ** $minorUnit) + ('' === $kept ? 0 : (int) str_pad($kept, $minorUnit, '0'));
        if ($increment) {
            ++$minor;
        }

        if (0 === $minorUnit) {
            return (string) $minor;
        }

        $factor = 10 ** $minorUnit;
        return intdiv($minor, $factor) . '.' . str_pad((string) ($minor % $factor), $minorUnit, '0', STR_PAD_LEFT);
    }
}
