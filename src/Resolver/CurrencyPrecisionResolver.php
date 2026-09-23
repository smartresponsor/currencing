<?php

declare(strict_types=1);

namespace App\Currencing\Resolver;

use App\Currencing\ServiceInterface\CurrencyCodeValidatorInterface;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;
use App\Currencing\ServiceInterface\CurrencyPrecisionResolverInterface;

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
