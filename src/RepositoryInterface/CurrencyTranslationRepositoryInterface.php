<?php

declare(strict_types=1);

namespace App\Currencing\RepositoryInterface;

use App\Currencing\Entity\Currency\CurrencyTranslationEntity;

interface CurrencyTranslationRepositoryInterface
{
    public function save(CurrencyTranslationEntity $translation): void;

    public function findOneByCurrencyIdAndLocale(int $currencyId, string $locale): ?CurrencyTranslationEntity;

    /** @return list<CurrencyTranslationEntity> */
    public function findByCurrencyId(int $currencyId): array;
}
