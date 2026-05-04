<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Template-safe formatted monetary amount.
 */
final readonly class MoneyDisplay
{
    public function __construct(
        private string $formatted,
        private int $minorUnits,
        private string $currencyCode,
        private string $decimalAmount,
        private ?string $locale = null,
    ) {
        $this->currencyCode = strtoupper($currencyCode);
    }

    public function getFormatted(): string
    {
        return $this->formatted;
    }

    public function getMinorUnits(): int
    {
        return $this->minorUnits;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getDecimalAmount(): string
    {
        return $this->decimalAmount;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return array{formatted:string,minorUnits:int,currencyCode:string,decimalAmount:string,locale:?string}
     */
    public function toArray(): array
    {
        return [
            'formatted' => $this->formatted,
            'minorUnits' => $this->minorUnits,
            'currencyCode' => $this->currencyCode,
            'decimalAmount' => $this->decimalAmount,
            'locale' => $this->locale,
        ];
    }
}
