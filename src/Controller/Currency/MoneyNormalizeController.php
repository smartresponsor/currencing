<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Enum\Currency\MoneyRoundingContext;
use App\Enum\Currency\MoneyRoundingMode;
use App\ServiceInterface\Currency\MonetaryAmountInputResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Normalizes external monetary input into canonical minor-unit money output.
 */
#[Route('/currencing/money/normalize', name: 'currencing_money_normalize', methods: ['POST'])]
final readonly class MoneyNormalizeController
{
    public function __construct(
        private MonetaryAmountInputResolverInterface $monetaryAmountInputResolver,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payload = $this->payload($request);

        if (!array_key_exists('amount', $payload) || !array_key_exists('currencyCode', $payload)) {
            return new JsonResponse([
                'component' => 'currencing',
                'resource' => 'money_normalization',
                'error' => 'amount and currencyCode are required.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $input = new MonetaryAmountInput(
                $payload['amount'],
                $this->requiredString($payload['currencyCode'], 'currencyCode'),
                $this->roundingMode($payload['roundingMode'] ?? null),
                $this->optionalString($payload['sourceComponent'] ?? null),
                $this->optionalString($payload['sourceReference'] ?? null),
                $this->optionalString($payload['locale'] ?? null),
                $this->optionalString($payload['roundingPolicyName'] ?? null),
                $this->roundingContext($payload['roundingContext'] ?? null),
            );

            $resolution = $this->monetaryAmountInputResolver->resolve($input);
            $policy = $resolution->getRoundingPolicy();

            return new JsonResponse([
                'component' => 'currencing',
                'resource' => 'money_normalization',
                'item' => [
                    'minorUnits' => $resolution->getMinorUnits(),
                    'currencyCode' => $resolution->getCurrencyCode(),
                    'minorUnit' => $resolution->getMinorUnit(),
                    'decimalAmount' => $resolution->getDecimalAmount(),
                    'formattedAmount' => $resolution->getFormattedAmount(),
                    'display' => $resolution->getMoneyDisplay()->toArray(),
                    'roundingPolicy' => null === $policy ? null : [
                        'name' => $policy->getName(),
                        'roundingMode' => $policy->getRoundingMode()->value,
                        'context' => $policy->getContext()->value,
                        'cashIncrementMinorUnits' => $policy->getCashIncrementMinorUnits(),
                        'description' => $policy->getDescription(),
                    ],
                ],
            ]);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'component' => 'currencing',
                'resource' => 'money_normalization',
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(Request $request): array
    {
        $content = trim($request->getContent());
        if ('' !== $content) {
            $decoded = json_decode($content, true);
            if (!is_array($decoded)) {
                throw new \InvalidArgumentException('Request body must be valid JSON object.');
            }

            return $decoded;
        }

        return $request->request->all();
    }

    private function requiredString(mixed $value, string $field): string
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('%s must be a string.', $field));
        }

        $value = trim($value);
        if ('' === $value) {
            throw new \InvalidArgumentException(sprintf('%s must not be empty.', $field));
        }

        return $value;
    }

    private function optionalString(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Optional string payload fields must be strings when provided.');
        }

        $value = trim($value);

        return '' === $value ? null : $value;
    }

    private function roundingMode(mixed $value): MoneyRoundingMode
    {
        if (null === $value || '' === $value) {
            return MoneyRoundingMode::Reject;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('roundingMode must be a string when provided.');
        }

        return MoneyRoundingMode::tryFrom(strtolower(trim($value)))
            ?? throw new \InvalidArgumentException(sprintf('Unsupported roundingMode "%s".', $value));
    }

    private function roundingContext(mixed $value): ?MoneyRoundingContext
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('roundingContext must be a string when provided.');
        }

        return MoneyRoundingContext::tryFrom(strtolower(trim($value)))
            ?? throw new \InvalidArgumentException(sprintf('Unsupported roundingContext "%s".', $value));
    }
}
