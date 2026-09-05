<?php

declare(strict_types=1);

namespace App\Dto\Currency;

use App\Enum\Currency\CurrencyRoundingContext;
use App\Enum\Currency\CurrencyRoundingMode;

/**
 * Input DTO used by neighboring components when they hand monetary values to
 * Currencing for normalization.
 *
 * The DTO deliberately accepts string/int/float because UI forms, imported CSV
 * rows, provider payloads, and internal commands may arrive in different shapes.
 * The resolver is responsible for converting this input into a canonical
 * CurrencyAmountDTO DTO.
 */
final readonly class CurrencyAmountInputDTO
{
    private string|int|float $amount;
    private string $currencyCode;
    private CurrencyRoundingMode $roundingMode;
    private ?string $sourceComponent;
    private ?string $sourceReference;
    private ?string $locale;
    private ?string $roundingPolicyName;
    private ?CurrencyRoundingContext $roundingContext;

    public function __construct(
        string|int|float $amount,
        string $currencyCode,
        CurrencyRoundingMode $roundingMode = CurrencyRoundingMode::Reject,
        ?string $sourceComponent = null,
        ?string $sourceReference = null,
        ?string $locale = null,
        ?string $roundingPolicyName = null,
        ?CurrencyRoundingContext $roundingContext = null,
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

    public function getRoundingMode(): CurrencyRoundingMode
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

    public function getRoundingContext(): ?CurrencyRoundingContext
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
