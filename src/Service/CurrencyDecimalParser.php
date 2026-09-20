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
        if ($minorUnit < 0 || $minorUnit > 8) {
            throw new \InvalidArgumentException('Currency minor unit must be between 0 and 8.');
        }

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
        $minorDigits = $this->normalizeUnsignedInteger($whole.$fraction);

        return $this->toSignedInteger($minorDigits, $negative, $decimal);
    }

    public function formatFromMinorUnits(int $amountMinor, int $minorUnit): string
    {
        if ($minorUnit < 0 || $minorUnit > 8) {
            throw new \InvalidArgumentException('Currency minor unit must be between 0 and 8.');
        }

        $negative = $amountMinor < 0;
        $digits = ltrim((string) $amountMinor, '-');

        if (0 === $minorUnit) {
            return ($negative ? '-' : '').$digits;
        }

        $digits = str_pad($digits, $minorUnit + 1, '0', STR_PAD_LEFT);
        $whole = substr($digits, 0, -$minorUnit);
        $fraction = substr($digits, -$minorUnit);

        return sprintf('%s%s.%s', $negative ? '-' : '', $whole, $fraction);
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
        $kept = str_pad(substr($fraction, 0, $minorUnit), $minorUnit, '0');
        $discarded = substr($fraction, $minorUnit);
        $increment = match ($roundingMode) {
            CurrencyRoundingMode::Down => false,
            CurrencyRoundingMode::Up => '' !== trim($discarded, '0'),
            CurrencyRoundingMode::HalfUp => '' !== $discarded && ((int) $discarded[0]) >= 5,
            CurrencyRoundingMode::Reject => false,
        };

        $minorDigits = $this->normalizeUnsignedInteger($whole.$kept);
        if ($increment) {
            $minorDigits = $this->incrementUnsignedInteger($minorDigits);
        }

        if (0 === $minorUnit) {
            return $minorDigits;
        }

        $minorDigits = str_pad($minorDigits, $minorUnit + 1, '0', STR_PAD_LEFT);

        return substr($minorDigits, 0, -$minorUnit).'.'.substr($minorDigits, -$minorUnit);
    }

    private function normalizeUnsignedInteger(string $digits): string
    {
        $normalized = ltrim($digits, '0');

        return '' === $normalized ? '0' : $normalized;
    }

    private function incrementUnsignedInteger(string $digits): string
    {
        $digits = $this->normalizeUnsignedInteger($digits);
        $carry = 1;

        for ($index = strlen($digits) - 1; $index >= 0 && 1 === $carry; --$index) {
            $value = ((int) $digits[$index]) + $carry;
            $digits[$index] = (string) ($value % 10);
            $carry = intdiv($value, 10);
        }

        return 1 === $carry ? '1'.$digits : $digits;
    }

    private function toSignedInteger(string $minorDigits, bool $negative, string $sourceAmount): int
    {
        $positiveLimit = (string) PHP_INT_MAX;
        $negativeLimit = $this->incrementUnsignedInteger($positiveLimit);
        $limit = $negative ? $negativeLimit : $positiveLimit;

        if (strlen($minorDigits) > strlen($limit)
            || (strlen($minorDigits) === strlen($limit) && strcmp($minorDigits, $limit) > 0)
        ) {
            throw CurrencyInvalidAmountException::outOfRange($sourceAmount);
        }

        if ($negative && $minorDigits === $negativeLimit) {
            return PHP_INT_MIN;
        }

        $minor = (int) $minorDigits;

        return $negative ? -$minor : $minor;
    }
}
