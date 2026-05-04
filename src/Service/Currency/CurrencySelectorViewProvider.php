<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\CurrencySelectorView;
use App\ServiceInterface\Currency\CurrencyChoiceProviderInterface;
use App\ServiceInterface\Currency\CurrencySelectorViewProviderInterface;

final class CurrencySelectorViewProvider implements CurrencySelectorViewProviderInterface
{
    public function __construct(private readonly CurrencyChoiceProviderInterface $currencyChoiceProvider)
    {
    }

    public function selector(?string $selectedCode = null, ?string $locale = null): CurrencySelectorView
    {
        return new CurrencySelectorView(
            $this->currencyChoiceProvider->choices($locale),
            $selectedCode,
        );
    }
}
