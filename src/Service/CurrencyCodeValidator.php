<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\Exception\CurrencyUnsupportedCodeException;
use App\Currencing\ServiceInterface\CurrencyCodeValidatorInterface;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;

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
            throw CurrencyUnsupportedCodeException::forCode($currencyCode);
        }
    }
}
