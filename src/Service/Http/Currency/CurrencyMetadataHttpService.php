<?php

declare(strict_types=1);

namespace App\Currencing\Service\Http\Currency;

use App\Currencing\ServiceInterface\CurrencyMetadataViewProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Read API for currency metadata.
 *
 * The controller exposes DTO arrays only. Doctrine entities stay inside the
 * Currencing persistence/model layer.
 */
final readonly class CurrencyMetadataHttpService
{
    public function __construct(
        private CurrencyMetadataViewProviderInterface $currencyMetadataViewProvider,
    ) {
    }

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
