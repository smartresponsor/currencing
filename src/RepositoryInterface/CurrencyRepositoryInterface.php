<?php

declare(strict_types=1);

namespace App\Currencing\RepositoryInterface;

use App\Currencing\Entity\Currency\CurrencyEntity;
use App\Currencing\ValueObject\CurrencyCode;

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
