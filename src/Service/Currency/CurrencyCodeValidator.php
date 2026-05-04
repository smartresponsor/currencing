<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Exception\Currency\UnsupportedCurrencyCodeException;
use App\ServiceInterface\Currency\CurrencyCodeValidatorInterface;
use App\ServiceInterface\Currency\CurrencyMetadataProviderInterface;

final class CurrencyCodeValidator implements CurrencyCodeValidatorInterface
{
    public function __construct(private readonly CurrencyMetadataProviderInterface $currencyMetadataProvider)
    {
    }

    public function supports(string $currencyCode): bool
    {
        return in_array(strtoupper($currencyCode), $this->currencyMetadataProvider->knownCodes(), true);
    }

    public function assertSupported(string $currencyCode): void
    {
        if (!$this->supports($currencyCode)) {
            throw UnsupportedCurrencyCodeException::forCode($currencyCode);
        }
    }
}
