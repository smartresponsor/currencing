<?php

declare(strict_types=1);

namespace App\ValueObject\Currency;

use InvalidArgumentException;

/**
 * Small value object for documenting/validating neighboring component names
 * that consume Currencing contracts.
 */
final readonly class CurrencyConsumerName
{
    private const MAX_LENGTH = 64;

    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ('' === $value) {
            throw new InvalidArgumentException('Currency consumer name cannot be empty.');
        }

        if (self::MAX_LENGTH < mb_strlen($value)) {
            throw new InvalidArgumentException('Currency consumer name cannot be longer than 64 characters.');
        }

        if (1 !== preg_match('/^[A-Za-z][A-Za-z0-9_\\-]*$/', $value)) {
            throw new InvalidArgumentException('Currency consumer name must start with a letter and contain only letters, numbers, underscore, or dash.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
