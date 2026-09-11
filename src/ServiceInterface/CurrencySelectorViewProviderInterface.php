<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencySelectorViewDTO;

interface CurrencySelectorViewProviderInterface
{
    public function selector(?string $selectedCode = null, ?string $locale = null): CurrencySelectorViewDTO;
}
