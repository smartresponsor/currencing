<?php

declare(strict_types=1);

namespace App\Currencing\DTO;

/**
 * Currency selector model consumed by templating/UI bridge layers.
 */
final readonly class CurrencySelectorViewDTO
{
    private ?string $selectedCode;

    /**
     * @param list<CurrencyChoiceDTO> $choices
     */
    public function __construct(
        private array $choices,
        ?string $selectedCode = null,
        private string $placeholder = 'currency.form.placeholder',
    ) {
        $this->selectedCode = null === $selectedCode ? null : strtoupper($selectedCode);
    }

    /**
     * @return list<CurrencyChoiceDTO>
     */
    public function getChoices(): array
    {
        return $this->choices;
    }

    public function getSelectedCode(): ?string
    {
        return $this->selectedCode;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @return array{choices:list<array{code:string,label:string,symbol:?string,minorUnit:int}>,selectedCode:?string,placeholder:string}
     */
    public function toArray(): array
    {
        return [
            'choices' => array_map(static fn (CurrencyChoiceDTO $choice): array => $choice->toArray(), $this->choices),
            'selectedCode' => $this->selectedCode,
            'placeholder' => $this->placeholder,
        ];
    }
}
