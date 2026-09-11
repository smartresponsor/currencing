<?php

declare(strict_types=1);

namespace App\Currencing\Service\Http\Currency;

use App\Currencing\ServiceInterface\CurrencyConversionBoundaryProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Exposes the Currencing/Exchanging boundary for host applications and automation.
 */
final class CurrencyConversionBoundaryHttpService
{
    public function __construct(
        private readonly CurrencyConversionBoundaryProviderInterface $boundaryProvider,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $boundary = $this->boundaryProvider->provideBoundary();

        return new JsonResponse([
            'currencingResponsibilities' => $boundary->currencingResponsibilities,
            'exchangingResponsibilities' => $boundary->exchangingResponsibilities,
            'forbiddenCurrencingResponsibilities' => $boundary->forbiddenCurrencingResponsibilities,
            'dependencyDirection' => $boundary->dependencyDirection,
        ]);
    }
}
