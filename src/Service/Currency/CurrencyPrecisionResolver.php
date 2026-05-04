<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\ServiceInterface\Currency\CurrencyCodeValidatorInterface;
use App\ServiceInterface\Currency\CurrencyMetadataProviderInterface;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;

final class CurrencyPrecisionResolver implements CurrencyPrecisionResolverInterface
{
    public function __construct(
        private readonly CurrencyMetadataProviderInterface $currencyMetadataProvider,
        private readonly CurrencyCodeValidatorInterface $currencyCodeValidator,
    ) {
    }

    public function minorUnitFor(string $currencyCode): int
    {
        $currencyCode = strtoupper($currencyCode);
        $this->currencyCodeValidator->assertSupported($currencyCode);

        return (int) $this->currencyMetadataProvider->metadataFor($currencyCode)['minorUnit'];
    }

    public function factorFor(string $currencyCode): int
    {
        return 10 ** $this->minorUnitFor($currencyCode);
    }
}
