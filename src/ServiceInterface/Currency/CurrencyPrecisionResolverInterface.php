<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

interface CurrencyPrecisionResolverInterface
{
    public function minorUnitFor(string $currencyCode): int;

    public function factorFor(string $currencyCode): int;
}
