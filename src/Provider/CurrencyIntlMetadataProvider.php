<?php

declare(strict_types=1);

namespace App\Currencing\Provider;

use App\Currencing\Repository\CurrencyRepository;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;
use Symfony\Component\Intl\Currencies;

final class CurrencyIntlMetadataProvider implements CurrencyMetadataProviderInterface
{
    public function __construct(private readonly CurrencyRepository $currencyRepository)
    {
    }

    public function metadataFor(string $code, ?string $locale = null): array
    {
        $code = strtoupper($code);
        $stored = $this->currencyRepository->findOneActiveByCode($code);

        return [
            'code' => $code,
            'numericCode' => $stored?->getNumericCode(),
            'minorUnit' => $stored?->getMinorUnit() ?? Currencies::getFractionDigits($code),
            'symbol' => $stored?->getSymbol() ?? Currencies::getSymbol($code, $locale),
            'displayName' => $stored?->getDisplayName() ?? Currencies::getName($code, $locale),
        ];
    }

    public function knownCodes(): array
    {
        $storedCodes = array_map(
            static fn ($currency): string => $currency->getCode(),
            $this->currencyRepository->findActiveOrderedByCode(),
        );

        return [] !== $storedCodes ? $storedCodes : Currencies::getCurrencyCodes();
    }
}
