<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyConversionBoundary;

/**
 * Provides the canonical boundary between Currencing and Exchanging.
 */
interface CurrencyConversionBoundaryProviderInterface
{
    public function provideBoundary(): CurrencyConversionBoundary;
}
