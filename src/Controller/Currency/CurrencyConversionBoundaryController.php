<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\ServiceInterface\Currency\CurrencyConversionBoundaryProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Exposes the Currencing/Exchanging boundary for host applications and automation.
 */
final class CurrencyConversionBoundaryController extends AbstractController
{
    public function __construct(
        private readonly CurrencyConversionBoundaryProviderInterface $boundaryProvider,
    ) {
    }

    #[Route('/currencing/conversion-boundary', name: 'currencing_conversion_boundary', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $boundary = $this->boundaryProvider->provideBoundary();

        return $this->json([
            'currencingResponsibilities' => $boundary->currencingResponsibilities,
            'exchangingResponsibilities' => $boundary->exchangingResponsibilities,
            'forbiddenCurrencingResponsibilities' => $boundary->forbiddenCurrencingResponsibilities,
            'dependencyDirection' => $boundary->dependencyDirection,
        ]);
    }
}
