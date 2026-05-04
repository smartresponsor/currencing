<?php

declare(strict_types=1);

namespace App\Dto\Currency;

/**
 * Documents the Currencing/Exchanging boundary as machine-readable component metadata.
 *
 * This DTO is intentionally static/read-only. It helps agents, host apps, and future
 * integration code identify which component owns which monetary responsibility.
 */
final readonly class CurrencyConversionBoundary
{
    /**
     * @param list<string> $currencingResponsibilities
     * @param list<string> $exchangingResponsibilities
     * @param list<string> $forbiddenCurrencingResponsibilities
     */
    public function __construct(
        public array $currencingResponsibilities,
        public array $exchangingResponsibilities,
        public array $forbiddenCurrencingResponsibilities,
        public string $dependencyDirection,
    ) {
    }
}
