<?php

declare(strict_types=1);

namespace App\Dto\Currency;

use App\ValueObject\Currency\CurrencyCode;

/**
 * Describes a currency conversion request without resolving rates.
 *
 * Currencing may validate and normalize the monetary input that would be converted, but
 * it must not fetch exchange rates or calculate FX quotes. Exchanging is the component
 * that should consume this DTO after the monetary amount is normalized.
 */
final readonly class CurrencyConversionIntent
{
    public CurrencyCode $sourceCurrencyCode;

    public CurrencyCode $targetCurrencyCode;

    public function __construct(
        public MoneyAmount $sourceMoneyAmount,
        CurrencyCode|string $targetCurrencyCode,
        public ?string $consumerName = null,
        public ?string $correlationId = null,
    ) {
        $this->sourceCurrencyCode = CurrencyCode::fromString($sourceMoneyAmount->currencyCode);

        $targetCode = $targetCurrencyCode instanceof CurrencyCode
            ? $targetCurrencyCode
            : CurrencyCode::fromString($targetCurrencyCode);

        if ($this->sourceCurrencyCode->equals($targetCode)) {
            throw new \InvalidArgumentException('Currency conversion intent requires different source and target currencies.');
        }

        $this->targetCurrencyCode = $targetCode;
    }

    public function sourceCode(): string
    {
        return $this->sourceCurrencyCode->value;
    }

    public function targetCode(): string
    {
        return $this->targetCurrencyCode->value;
    }
}
