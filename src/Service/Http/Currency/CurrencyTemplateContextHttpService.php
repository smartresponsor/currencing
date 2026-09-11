<?php

declare(strict_types=1);

namespace App\Currencing\Service\Http\Currency;

use App\Currencing\ServiceInterface\CurrencyTemplateContextProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Read API for Bridge/Interfacing template composition.
 */
final readonly class CurrencyTemplateContextHttpService
{
    public function __construct(private CurrencyTemplateContextProviderInterface $currencyTemplateContextProvider)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $selectedCode = $this->optionalString($request->query->get('selectedCode'));
        $locale = $this->optionalString($request->query->get('locale'));

        return new JsonResponse([
            'component' => 'currencing',
            'resource' => 'currency_template_context',
            'item' => $this->currencyTemplateContextProvider->context($selectedCode, $locale)->toArray(),
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
