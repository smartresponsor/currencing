<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencySelectorView;

interface CurrencySelectorViewProviderInterface
{
    public function selector(?string $selectedCode = null, ?string $locale = null): CurrencySelectorView;
}
