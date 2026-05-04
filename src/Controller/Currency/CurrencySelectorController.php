<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\ServiceInterface\Currency\CurrencySelectorViewProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Read API for a template-safe currency selector model.
 */
#[Route('/currencing/currency-selector', name: 'currencing_currency_selector', methods: ['GET'])]
final readonly class CurrencySelectorController
{
    public function __construct(
        private CurrencySelectorViewProviderInterface $currencySelectorViewProvider,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $selectedCode = $this->optionalString($request->query->get('selectedCode'));
        $locale = $this->optionalString($request->query->get('locale'));

        return new JsonResponse([
            'component' => 'currencing',
            'resource' => 'currency_selector',
            'item' => $this->currencySelectorViewProvider->selector($selectedCode, $locale)->toArray(),
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
