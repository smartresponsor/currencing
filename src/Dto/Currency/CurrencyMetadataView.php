<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Read-only currency metadata view for forms, APIs, and UI bridges.
 */
final readonly class CurrencyMetadataView
{
    public function __construct(
        private string $code,
        private ?string $numericCode,
        private int $minorUnit,
        private ?string $symbol,
        private ?string $displayName,
    ) {
        $this->code = strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getNumericCode(): ?string
    {
        return $this->numericCode;
    }

    public function getMinorUnit(): int
    {
        return $this->minorUnit;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @return array{code:string,numericCode:?string,minorUnit:int,symbol:?string,displayName:?string}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'numericCode' => $this->numericCode,
            'minorUnit' => $this->minorUnit,
            'symbol' => $this->symbol,
            'displayName' => $this->displayName,
        ];
    }
}
