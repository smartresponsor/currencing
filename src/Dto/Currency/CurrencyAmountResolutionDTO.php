<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Canonical resolved monetary output for neighboring components.
 *
 * Consumers should store/use the CurrencyAmountDTO minor-unit value for business
 * calculations and may pass CurrencyDisplayDTO to templates/UI layers.
 */
final readonly class CurrencyAmountResolutionDTO
{
    public function __construct(
        private CurrencyAmountInputDTO $input,
        private CurrencyAmountDTO $moneyAmount,
        private CurrencyDisplayDTO $moneyDisplay,
        private int $minorUnit,
        private ?CurrencyRoundingPolicyDTO $roundingPolicy = null,
    ) {
    }

    public function getInput(): CurrencyAmountInputDTO
    {
        return $this->input;
    }

    public function getMoneyAmount(): CurrencyAmountDTO
    {
        return $this->moneyAmount;
    }

    public function getMoneyDisplay(): CurrencyDisplayDTO
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

    public function getRoundingPolicy(): ?CurrencyRoundingPolicyDTO
    {
        return $this->roundingPolicy;
    }
}
