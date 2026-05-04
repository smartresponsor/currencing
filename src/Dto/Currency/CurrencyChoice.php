<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Template/form-safe currency choice item.
 */
final readonly class CurrencyChoice
{
    public function __construct(
        private string $code,
        private string $label,
        private ?string $symbol = null,
        private int $minorUnit = 2,
    ) {
        $this->code = strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function getMinorUnit(): int
    {
        return $this->minorUnit;
    }

    /**
     * @return array{code:string,label:string,symbol:?string,minorUnit:int}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'label' => $this->label,
            'symbol' => $this->symbol,
            'minorUnit' => $this->minorUnit,
        ];
    }
}
