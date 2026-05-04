<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\ServiceInterface\Currency\CurrencyMetadataViewProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Read API for currency metadata.
 *
 * The controller exposes DTO arrays only. Doctrine entities stay inside the
 * Currencing persistence/model layer.
 */
#[Route('/currencing/currencies', name: 'currencing_currency_')]
final readonly class CurrencyMetadataController
{
    public function __construct(
        private CurrencyMetadataViewProviderInterface $currencyMetadataViewProvider,
    ) {
    }

    #[Route('', name: 'catalog', methods: ['GET'])]
    public function catalog(Request $request): JsonResponse
    {
        $locale = $this->optionalString($request->query->get('locale'));

        return new JsonResponse([
            'component' => 'currencing',
            'resource' => 'currency_catalog',
            'items' => array_map(
                static fn ($view): array => $view->toArray(),
                $this->currencyMetadataViewProvider->allViews($locale),
            ),
        ]);
    }

    #[Route('/{code}', name: 'metadata', requirements: ['code' => '[A-Za-z]{3}'], methods: ['GET'])]
    public function metadata(string $code, Request $request): JsonResponse
    {
        $locale = $this->optionalString($request->query->get('locale'));

        return new JsonResponse([
            'component' => 'currencing',
            'resource' => 'currency_metadata',
            'item' => $this->currencyMetadataViewProvider->viewFor($code, $locale)->toArray(),
        ]);
    }

    private function optionalString(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        return '' === $value ? null : $value;
    }
}
