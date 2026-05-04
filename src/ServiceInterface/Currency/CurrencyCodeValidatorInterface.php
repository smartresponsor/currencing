<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

interface CurrencyCodeValidatorInterface
{
    public function supports(string $currencyCode): bool;

    public function assertSupported(string $currencyCode): void;
}
