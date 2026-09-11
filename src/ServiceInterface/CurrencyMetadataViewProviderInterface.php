<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyMetadataViewDTO;

interface CurrencyMetadataViewProviderInterface
{
    public function viewFor(string $currencyCode, ?string $locale = null): CurrencyMetadataViewDTO;

    /**
     * @return list<CurrencyMetadataViewDTO>
     */
    public function allViews(?string $locale = null): array;
}
