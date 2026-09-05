<?php

declare(strict_types=1);

namespace App\RepositoryInterface\Currency;

use App\Entity\Currency\CurrencyEntity;
use App\ValueObject\Currency\CurrencyCode;

interface CurrencyRepositoryInterface
{
    public function findOneByCode(string|CurrencyCode $code): ?CurrencyEntity;

    public function findOneActiveByCode(string|CurrencyCode $code): ?CurrencyEntity;

    public function hasActiveCode(string|CurrencyCode $code): bool;

    /** @return list<CurrencyEntity> */
    public function findActiveOrderedByCode(): array;

    /** @return list<string> */
    public function findActiveCodesOrdered(): array;
}
