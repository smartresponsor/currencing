<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Currency;

use App\Entity\Currency\CurrencyExchangeEntity;

interface CurrencyExchangeRepositoryInterface
{
    public function save(CurrencyExchangeEntity $exchange): void;

    public function findLatestRate(string $baseCurrencyCode, string $targetCurrencyCode): ?CurrencyExchangeEntity;
}
