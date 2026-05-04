<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyMetadataView;

interface CurrencyMetadataViewProviderInterface
{
    public function viewFor(string $currencyCode, ?string $locale = null): CurrencyMetadataView;

    /**
     * @return list<CurrencyMetadataView>
     */
    public function allViews(?string $locale = null): array;
}
