<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Immutable normalized monetary amount.
 *
 * Amounts are stored in minor units to avoid leaking float arithmetic into
 * Ordering, Paying, Taxating, Shipping, Subscription, Discounting, and other
 * consumers.
 */
final readonly class MoneyAmount
{
    public function __construct(
        private int $minorUnits,
        private string $currencyCode,
    ) {
        $this->currencyCode = strtoupper($currencyCode);
    }

    public function getMinorUnits(): int
    {
        return $this->minorUnits;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
