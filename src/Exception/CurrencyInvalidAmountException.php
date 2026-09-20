<?php

declare(strict_types=1);

namespace App\Currencing\Exception;

final class CurrencyInvalidAmountException extends \InvalidArgumentException
{
    public static function forAmount(string|int|float $amount): self
    {
        return new self(sprintf('Invalid monetary amount: "%s".', (string) $amount));
    }

    public static function tooPrecise(string $amount, string $currencyCode, int $minorUnit): self
    {
        return new self(sprintf(
            'Monetary amount "%s" has more fractional digits than %s supports (%d).',
            $amount,
            strtoupper($currencyCode),
            $minorUnit,
        ));
    }

    public static function outOfRange(string $amount): self
    {
        return new self(sprintf(
            'Monetary amount "%s" exceeds the supported integer minor-unit range.',
            $amount,
        ));
    }
}
