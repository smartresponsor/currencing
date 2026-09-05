<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\CurrencyMetadataViewDTO;
use App\ServiceInterface\Currency\CurrencyMetadataProviderInterface;
use App\ServiceInterface\Currency\CurrencyMetadataViewProviderInterface;

final class CurrencyMetadataViewProvider implements CurrencyMetadataViewProviderInterface
{
    public function __construct(private readonly CurrencyMetadataProviderInterface $currencyMetadataProvider)
    {
    }

    public function viewFor(string $currencyCode, ?string $locale = null): CurrencyMetadataViewDTO
    {
        $metadata = $this->currencyMetadataProvider->metadataFor($currencyCode, $locale);

        return new CurrencyMetadataViewDTO(
            $metadata['code'],
            $metadata['numericCode'],
            $metadata['minorUnit'],
            $metadata['symbol'],
            $metadata['displayName'],
        );
    }

    public function allViews(?string $locale = null): array
    {
        return array_map(
            fn (string $code): CurrencyMetadataViewDTO => $this->viewFor($code, $locale),
            $this->currencyMetadataProvider->knownCodes(),
        );
    }
}
