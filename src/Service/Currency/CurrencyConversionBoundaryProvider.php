<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\CurrencyConversionBoundary;
use App\ServiceInterface\Currency\CurrencyConversionBoundaryProviderInterface;

/**
 * Provides the fixed component boundary for currency conversion responsibilities.
 */
final readonly class CurrencyConversionBoundaryProvider implements CurrencyConversionBoundaryProviderInterface
{
    public function provideBoundary(): CurrencyConversionBoundary
    {
        return new CurrencyConversionBoundary(
            currencingResponsibilities: [
                'ISO currency code validation',
                'currency metadata',
                'minor-unit precision',
                'money amount normalization',
                'money display formatting',
                'rounding policy selection',
                'conversion intent validation',
            ],
            exchangingResponsibilities: [
                'exchange-rate sourcing',
                'exchange-rate provider synchronization',
                'currency conversion quotes',
                'historical exchange rates',
                'FX spread/markup policy',
                'converted amount calculation',
            ],
            forbiddenCurrencingResponsibilities: [
                'live exchange-rate fetching',
                'historical FX lookup',
                'conversion quote pricing',
                'provider-specific exchange-rate adapters',
                'payment provider conversion behavior',
            ],
            dependencyDirection: 'Exchanging may depend on Currencing; Currencing must not depend on Exchanging.',
        );
    }
}
