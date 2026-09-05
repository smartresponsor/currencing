<?php

declare(strict_types=1);

namespace App\Exception\Currency;

final class CurrencyUnsupportedCodeException extends \InvalidArgumentException
{
    public static function forCode(string $currencyCode): self
    {
        return new self(sprintf('Unsupported currency code: "%s".', strtoupper($currencyCode)));
    }
}
