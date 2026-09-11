<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

interface CurrencyCodeValidatorInterface
{
    public function supports(string $currencyCode): bool;

    public function assertSupported(string $currencyCode): void;
}
