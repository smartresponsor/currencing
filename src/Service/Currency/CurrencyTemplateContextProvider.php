<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\CurrencyTemplateContextDTO;
use App\ServiceInterface\Currency\CurrencyMetadataViewProviderInterface;
use App\ServiceInterface\Currency\CurrencySelectorViewProviderInterface;
use App\ServiceInterface\Currency\CurrencyTemplateContextProviderInterface;

final class CurrencyTemplateContextProvider implements CurrencyTemplateContextProviderInterface
{
    public function __construct(
        private readonly CurrencySelectorViewProviderInterface $currencySelectorViewProvider,
        private readonly CurrencyMetadataViewProviderInterface $currencyMetadataViewProvider,
    ) {
    }

    public function context(?string $selectedCode = null, ?string $locale = null): CurrencyTemplateContextDTO
    {
        return new CurrencyTemplateContextDTO(
            $this->currencySelectorViewProvider->selector($selectedCode, $locale),
            $this->currencyMetadataViewProvider->allViews($locale),
            $selectedCode,
            $locale,
        );
    }
}
