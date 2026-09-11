<?php

declare(strict_types=1);

namespace App\Currencing\DTO;

use App\Currencing\Enum\CurrencyRoundingContext;
use App\Currencing\Enum\CurrencyRoundingMode;

/**
 * DTO describing the monetary rounding policy selected for normalization.
 */
final readonly class CurrencyRoundingPolicyDTO
{
    public function __construct(
        private string $nameEntity,
        private CurrencyRoundingMode $roundingMode,
        private CurrencyRoundingContext $context = CurrencyRoundingContext::Canonical,
        private ?int $cashIncrementMinorUnits = null,
        private ?string $description = null,
    ) {
        $this->assertValidName($nameEntity);

        if (null !== $cashIncrementMinorUnits && $cashIncrementMinorUnits < 1) {
            throw new \InvalidArgumentException('Cash increment minor units must be positive when provided.');
        }
    }

    public static function fromInputMode(CurrencyRoundingMode $roundingMode): self
    {
        return new self('input.'.$roundingMode->value, $roundingMode, CurrencyRoundingContext::Canonical);
    }

    public function getName(): string
    {
        return $this->nameEntity;
    }

    public function getRoundingMode(): CurrencyRoundingMode
    {
        return $this->roundingMode;
    }

    public function getContext(): CurrencyRoundingContext
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

    private function assertValidName(string $nameEntity): void
    {
        if (!preg_match('/^[a-z][a-z0-9_.-]{1,63}$/', $nameEntity)) {
            throw new \InvalidArgumentException(sprintf('Invalid money rounding policy nameEntity "%s".', $nameEntity));
        }
    }
}
