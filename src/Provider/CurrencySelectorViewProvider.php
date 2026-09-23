<?php

declare(strict_types=1);

namespace App\Currencing\Provider;

use App\Currencing\DTO\CurrencySelectorViewDTO;
use App\Currencing\ServiceInterface\CurrencyChoiceProviderInterface;
use App\Currencing\ServiceInterface\CurrencySelectorViewProviderInterface;

final class CurrencySelectorViewProvider implements CurrencySelectorViewProviderInterface
{
    public function __construct(private readonly CurrencyChoiceProviderInterface $currencyChoiceProvider)
    {
    }

    public function selector(?string $selectedCode = null, ?string $locale = null): CurrencySelectorViewDTO
    {
        return new CurrencySelectorViewDTO(
            $this->currencyChoiceProvider->choices($locale),
            $selectedCode,
        );
    }
}
