<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\DTO\CurrencyTemplateContextDTO;
use App\Currencing\ServiceInterface\CurrencyMetadataViewProviderInterface;
use App\Currencing\ServiceInterface\CurrencySelectorViewProviderInterface;
use App\Currencing\ServiceInterface\CurrencyTemplateContextProviderInterface;

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
