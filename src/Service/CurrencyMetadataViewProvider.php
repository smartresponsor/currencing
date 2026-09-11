<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\DTO\CurrencyMetadataViewDTO;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;
use App\Currencing\ServiceInterface\CurrencyMetadataViewProviderInterface;

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
