<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

interface CurrencyPrecisionResolverInterface
{
    public function minorUnitFor(string $currencyCode): int;

    public function factorFor(string $currencyCode): int;
}
