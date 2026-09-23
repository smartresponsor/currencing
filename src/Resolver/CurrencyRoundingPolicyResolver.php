<?php

declare(strict_types=1);

namespace App\Currencing\Resolver;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyRoundingPolicyDTO;
use App\Currencing\Enum\CurrencyRoundingContext;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\ServiceInterface\CurrencyRoundingPolicyResolverInterface;
use App\Currencing\ValueObject\CurrencyRoundingPolicyName;

/**
 * Resolves component-safe monetary rounding policies.
 *
 * The default policy is intentionally strict/reject. Business components may opt
 * into a named/context policy when they want rounding instead of rejection.
 */
final class CurrencyRoundingPolicyResolver implements CurrencyRoundingPolicyResolverInterface
{
    /** @var array<string, CurrencyRoundingPolicyDTO> */
    private array $policies;

    public function __construct()
    {
        $this->policies = $this->buildDefaultPolicies();
    }

    public function resolveForInput(CurrencyAmountInputDTO $input): CurrencyRoundingPolicyDTO
    {
        if (null !== $input->getRoundingPolicyName()) {
            return $this->resolveNamedPolicy($input->getRoundingPolicyName());
        }

        if (null !== $input->getRoundingContext()) {
            return $this->resolveForContext($input->getRoundingContext());
        }

        return CurrencyRoundingPolicyDTO::fromInputMode($input->getRoundingMode());
    }

    public function resolveNamedPolicy(string $policyName): CurrencyRoundingPolicyDTO
    {
        $nameEntity = (new CurrencyRoundingPolicyName($policyName))->value();

        if (!isset($this->policies[$nameEntity])) {
            throw new \InvalidArgumentException(sprintf('Unknown money rounding policy "%s".', $nameEntity));
        }

        return $this->policies[$nameEntity];
    }

    public function resolveForContext(CurrencyRoundingContext $context): CurrencyRoundingPolicyDTO
    {
        return match ($context) {
            CurrencyRoundingContext::Canonical => $this->resolveNamedPolicy('canonical.reject'),
            CurrencyRoundingContext::Ordering => $this->resolveNamedPolicy('ordering.reject'),
            CurrencyRoundingContext::Paying => $this->resolveNamedPolicy('paying.reject'),
            CurrencyRoundingContext::Taxating => $this->resolveNamedPolicy('taxating.reject'),
            CurrencyRoundingContext::Discounting => $this->resolveNamedPolicy('discounting.half_up'),
            CurrencyRoundingContext::Subscription => $this->resolveNamedPolicy('subscription.reject'),
            CurrencyRoundingContext::Formatting => $this->resolveNamedPolicy('formatting.half_up'),
            CurrencyRoundingContext::Cash => $this->resolveNamedPolicy('cash.down'),
        };
    }

    /** @return array<string, CurrencyRoundingPolicyDTO> */
    private function buildDefaultPolicies(): array
    {
        $policies = [
            new CurrencyRoundingPolicyDTO('canonical.reject', CurrencyRoundingMode::Reject, CurrencyRoundingContext::Canonical, description: 'Canonical storage normalization must reject over-precise amounts.'),
            new CurrencyRoundingPolicyDTO('ordering.reject', CurrencyRoundingMode::Reject, CurrencyRoundingContext::Ordering, description: 'Ordering totals should not silently round unless the caller opts into another policy.'),
            new CurrencyRoundingPolicyDTO('paying.reject', CurrencyRoundingMode::Reject, CurrencyRoundingContext::Paying, description: 'Payment payloads should match PSP minor-unit precision exactly.'),
            new CurrencyRoundingPolicyDTO('taxating.reject', CurrencyRoundingMode::Reject, CurrencyRoundingContext::Taxating, description: 'Tax calculation inputs should be explicit and auditable.'),
            new CurrencyRoundingPolicyDTO('discounting.half_up', CurrencyRoundingMode::HalfUp, CurrencyRoundingContext::Discounting, description: 'Discount display/value derivation may opt into half-up rounding.'),
            new CurrencyRoundingPolicyDTO('subscription.reject', CurrencyRoundingMode::Reject, CurrencyRoundingContext::Subscription, description: 'Recurring prices should be stored with exact currency precision.'),
            new CurrencyRoundingPolicyDTO('formatting.half_up', CurrencyRoundingMode::HalfUp, CurrencyRoundingContext::Formatting, description: 'Formatting-only projections may round for display.'),
            new CurrencyRoundingPolicyDTO('cash.down', CurrencyRoundingMode::Down, CurrencyRoundingContext::Cash, 1, 'Cash rounding policy for explicit cash workflows.'),
        ];

        $indexed = [];
        foreach ($policies as $policy) {
            $indexed[$policy->getName()] = $policy;
        }

        return $indexed;
    }
}
