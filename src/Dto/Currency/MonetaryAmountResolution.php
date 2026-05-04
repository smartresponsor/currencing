<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Canonical resolved monetary output for neighboring components.
 *
 * Consumers should store/use the MoneyAmount minor-unit value for business
 * calculations and may pass MoneyDisplay to template/UI layers.
 */
final readonly class MonetaryAmountResolution
{
    public function __construct(
        private MonetaryAmountInput $input,
        private MoneyAmount $moneyAmount,
        private MoneyDisplay $moneyDisplay,
        private int $minorUnit,
        private ?MoneyRoundingPolicy $roundingPolicy = null,
    ) {
    }

    public function getInput(): MonetaryAmountInput
    {
        return $this->input;
    }

    public function getMoneyAmount(): MoneyAmount
    {
        return $this->moneyAmount;
    }

    public function getMoneyDisplay(): MoneyDisplay
    {
        return $this->moneyDisplay;
    }

    public function getMinorUnits(): int
    {
        return $this->moneyAmount->getMinorUnits();
    }

    public function getCurrencyCode(): string
    {
        return $this->moneyAmount->getCurrencyCode();
    }

    public function getMinorUnit(): int
    {
        return $this->minorUnit;
    }

    public function getDecimalAmount(): string
    {
        return $this->moneyDisplay->getDecimalAmount();
    }

    public function getFormattedAmount(): string
    {
        return $this->moneyDisplay->getFormatted();
    }

    public function getRoundingPolicy(): ?MoneyRoundingPolicy
    {
        return $this->roundingPolicy;
    }
}
