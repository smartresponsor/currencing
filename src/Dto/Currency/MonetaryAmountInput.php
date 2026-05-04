<?php

declare(strict_types=1);

namespace App\Dto\Currency;

use App\Enum\Currency\MoneyRoundingContext;
use App\Enum\Currency\MoneyRoundingMode;

/**
 * Input DTO used by neighboring components when they hand monetary values to
 * Currencing for normalization.
 *
 * The DTO deliberately accepts string/int/float because UI forms, imported CSV
 * rows, provider payloads, and internal commands may arrive in different shapes.
 * The resolver is responsible for converting this input into a canonical
 * MoneyAmount DTO.
 */
final readonly class MonetaryAmountInput
{
    private string|int|float $amount;
    private string $currencyCode;
    private MoneyRoundingMode $roundingMode;
    private ?string $sourceComponent;
    private ?string $sourceReference;
    private ?string $locale;
    private ?string $roundingPolicyName;
    private ?MoneyRoundingContext $roundingContext;

    public function __construct(
        string|int|float $amount,
        string $currencyCode,
        MoneyRoundingMode $roundingMode = MoneyRoundingMode::Reject,
        ?string $sourceComponent = null,
        ?string $sourceReference = null,
        ?string $locale = null,
        ?string $roundingPolicyName = null,
        ?MoneyRoundingContext $roundingContext = null,
    ) {
        $this->amount = $amount;
        $this->currencyCode = strtoupper(trim($currencyCode));
        $this->roundingMode = $roundingMode;
        $this->sourceComponent = self::emptyToNull($sourceComponent);
        $this->sourceReference = self::emptyToNull($sourceReference);
        $this->locale = self::emptyToNull($locale);
        $this->roundingPolicyName = self::emptyToNull(null === $roundingPolicyName ? null : strtolower($roundingPolicyName));
        $this->roundingContext = $roundingContext;
    }

    public function getAmount(): string|int|float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getRoundingMode(): MoneyRoundingMode
    {
        return $this->roundingMode;
    }

    public function getSourceComponent(): ?string
    {
        return $this->sourceComponent;
    }

    public function getSourceReference(): ?string
    {
        return $this->sourceReference;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function getRoundingPolicyName(): ?string
    {
        return $this->roundingPolicyName;
    }

    public function getRoundingContext(): ?MoneyRoundingContext
    {
        return $this->roundingContext;
    }

    private static function emptyToNull(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = trim($value);

        return '' === $value ? null : $value;
    }
}
