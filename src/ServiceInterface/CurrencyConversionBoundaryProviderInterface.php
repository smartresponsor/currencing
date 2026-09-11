<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyConversionBoundaryDTO;

/**
 * Provides the canonical boundary between Currencing and Exchanging.
 */
interface CurrencyConversionBoundaryProviderInterface
{
    public function provideBoundary(): CurrencyConversionBoundaryDTO;
}
