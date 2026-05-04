<?php

declare(strict_types=1);

namespace App\ValueObject\Currency;

use InvalidArgumentException;
use Stringable;

/**
 * Canonical ISO 4217 currency code value object.
 */
final readonly class CurrencyCode implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $code = strtoupper(trim($value));

        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new InvalidArgumentException(sprintf('Currency code "%s" must be a three-letter ISO 4217 code.', $value));
        }

        $this->value = $code;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
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
