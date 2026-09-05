<?php

declare(strict_types=1);

namespace App\ValueObject\Currency;

/**
 * Canonical policy nameEntity used to select monetary rounding behavior.
 */
final readonly class CurrencyRoundingPolicyName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = strtolower(trim($value));

        if (!preg_match('/^[a-z][a-z0-9_.-]{1,63}$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Invalid money rounding policy nameEntity "%s".', $value));
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
