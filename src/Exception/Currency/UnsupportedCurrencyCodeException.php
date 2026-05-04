<?php

declare(strict_types=1);

namespace App\Exception\Currency;

use InvalidArgumentException;

final class UnsupportedCurrencyCodeException extends InvalidArgumentException
{
    public static function forCode(string $currencyCode): self
    {
        return new self(sprintf('Unsupported currency code: "%s".', strtoupper($currencyCode)));
    }
}
