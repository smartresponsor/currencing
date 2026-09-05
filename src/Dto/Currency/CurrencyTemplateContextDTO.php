<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Bridge-safe template context for rendering Currencing surfaces outside the component.
 *
 * The context intentionally exposes route names and DTO arrays instead of template markup,
 * Doctrine entities, framework form views, or HTTP responses. A UI composition
 * layer can map this model to its own component system without coupling Currencing to
 * the renderer implementation.
 */
final readonly class CurrencyTemplateContextDTO
{
    private ?string $selectedCode;

    /**
     * @param list<CurrencyMetadataViewDTO> $currencies
     * @param array<string,string>          $routeNames
     * @param list<string>                  $capabilities
     */
    public function __construct(
        private CurrencySelectorViewDTO $selector,
        private array $currencies,
        ?string $selectedCode = null,
        private ?string $locale = null,
        private string $componentKey = 'currencing.currency',
        private array $routeNames = [
            'catalog' => 'currencing_currency_catalog',
            'metadata' => 'currencing_currency_metadata',
            'selector' => 'currencing_currency_selector',
            'normalize' => 'currencing_money_normalize',
            'conversionBoundary' => 'currencing_conversion_boundary',
        ],
        private array $capabilities = [
            'currency-selector',
            'currency-metadata',
            'money-normalization',
            'conversion-boundary',
        ],
    ) {
        $this->selectedCode = null === $selectedCode ? null : strtoupper($selectedCode);
    }

    public function getComponentKey(): string
    {
        return $this->componentKey;
    }

    public function getSelector(): CurrencySelectorViewDTO
    {
        return $this->selector;
    }

    /**
     * @return list<CurrencyMetadataViewDTO>
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    public function getSelectedCode(): ?string
    {
        return $this->selectedCode;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return array<string,string>
     */
    public function getRouteNames(): array
    {
        return $this->routeNames;
    }

    /**
     * @return list<string>
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * @return array{
     *     componentKey:string,
     *     selectedCode:?string,
     *     locale:?string,
     *     selector:array{choices:list<array{code:string,label:string,symbol:?string,minorUnit:int}>,selectedCode:?string,placeholder:string},
     *     currencies:list<array{code:string,numericCode:?string,minorUnit:int,symbol:?string,displayName:?string}>,
     *     routeNames:array<string,string>,
     *     capabilities:list<string>
     * }
     */
    public function toArray(): array
    {
        return [
            'componentKey' => $this->componentKey,
            'selectedCode' => $this->selectedCode,
            'locale' => $this->locale,
            'selector' => $this->selector->toArray(),
            'currencies' => array_map(static fn (CurrencyMetadataViewDTO $view): array => $view->toArray(), $this->currencies),
            'routeNames' => $this->routeNames,
            'capabilities' => $this->capabilities,
        ];
    }
}
