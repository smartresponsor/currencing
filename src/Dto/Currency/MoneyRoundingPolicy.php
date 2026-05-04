<?php

declare(strict_types=1);

namespace App\Dto\Currency;

use App\Enum\Currency\MoneyRoundingContext;
use App\Enum\Currency\MoneyRoundingMode;

/**
 * DTO describing the monetary rounding policy selected for normalization.
 */
final readonly class MoneyRoundingPolicy
{
    public function __construct(
        private string $name,
        private MoneyRoundingMode $roundingMode,
        private MoneyRoundingContext $context = MoneyRoundingContext::Canonical,
        private ?int $cashIncrementMinorUnits = null,
        private ?string $description = null,
    ) {
        $this->assertValidName($name);

        if (null !== $cashIncrementMinorUnits && $cashIncrementMinorUnits < 1) {
            throw new \InvalidArgumentException('Cash increment minor units must be positive when provided.');
        }
    }

    public static function fromInputMode(MoneyRoundingMode $roundingMode): self
    {
        return new self('input.' . $roundingMode->value, $roundingMode, MoneyRoundingContext::Canonical);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoundingMode(): MoneyRoundingMode
    {
        return $this->roundingMode;
    }

    public function getContext(): MoneyRoundingContext
    {
        return $this->context;
    }

    public function getCashIncrementMinorUnits(): ?int
    {
        return $this->cashIncrementMinorUnits;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    private function assertValidName(string $name): void
    {
        if (!preg_match('/^[a-z][a-z0-9_.-]{1,63}$/', $name)) {
            throw new \InvalidArgumentException(sprintf('Invalid money rounding policy name "%s".', $name));
        }
    }
}
