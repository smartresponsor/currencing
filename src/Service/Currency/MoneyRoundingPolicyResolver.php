<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Dto\Currency\MoneyRoundingPolicy;
use App\Enum\Currency\MoneyRoundingContext;
use App\Enum\Currency\MoneyRoundingMode;
use App\ServiceInterface\Currency\MoneyRoundingPolicyResolverInterface;
use App\ValueObject\Currency\MoneyRoundingPolicyName;

/**
 * Resolves component-safe monetary rounding policies.
 *
 * The default policy is intentionally strict/reject. Business components may opt
 * into a named/context policy when they want rounding instead of rejection.
 */
final class MoneyRoundingPolicyResolver implements MoneyRoundingPolicyResolverInterface
{
    /** @var array<string, MoneyRoundingPolicy> */
    private array $policies;

    public function __construct()
    {
        $this->policies = $this->buildDefaultPolicies();
    }

    public function resolveForInput(MonetaryAmountInput $input): MoneyRoundingPolicy
    {
        if (null !== $input->getRoundingPolicyName()) {
            return $this->resolveNamedPolicy($input->getRoundingPolicyName());
        }

        if (null !== $input->getRoundingContext()) {
            return $this->resolveForContext($input->getRoundingContext());
        }

        return MoneyRoundingPolicy::fromInputMode($input->getRoundingMode());
    }

    public function resolveNamedPolicy(string $policyName): MoneyRoundingPolicy
    {
        $name = (new MoneyRoundingPolicyName($policyName))->value();

        if (!isset($this->policies[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknown money rounding policy "%s".', $name));
        }

        return $this->policies[$name];
    }

    public function resolveForContext(MoneyRoundingContext $context): MoneyRoundingPolicy
    {
        return match ($context) {
            MoneyRoundingContext::Canonical => $this->resolveNamedPolicy('canonical.reject'),
            MoneyRoundingContext::Ordering => $this->resolveNamedPolicy('ordering.reject'),
            MoneyRoundingContext::Paying => $this->resolveNamedPolicy('paying.reject'),
            MoneyRoundingContext::Taxating => $this->resolveNamedPolicy('taxating.reject'),
            MoneyRoundingContext::Discounting => $this->resolveNamedPolicy('discounting.half_up'),
            MoneyRoundingContext::Subscription => $this->resolveNamedPolicy('subscription.reject'),
            MoneyRoundingContext::Formatting => $this->resolveNamedPolicy('formatting.half_up'),
            MoneyRoundingContext::Cash => $this->resolveNamedPolicy('cash.down'),
        };
    }

    /** @return array<string, MoneyRoundingPolicy> */
    private function buildDefaultPolicies(): array
    {
        $policies = [
            new MoneyRoundingPolicy('canonical.reject', MoneyRoundingMode::Reject, MoneyRoundingContext::Canonical, description: 'Canonical storage normalization must reject over-precise amounts.'),
            new MoneyRoundingPolicy('ordering.reject', MoneyRoundingMode::Reject, MoneyRoundingContext::Ordering, description: 'Ordering totals should not silently round unless the caller opts into another policy.'),
            new MoneyRoundingPolicy('paying.reject', MoneyRoundingMode::Reject, MoneyRoundingContext::Paying, description: 'Payment payloads should match PSP minor-unit precision exactly.'),
            new MoneyRoundingPolicy('taxating.reject', MoneyRoundingMode::Reject, MoneyRoundingContext::Taxating, description: 'Tax calculation inputs should be explicit and auditable.'),
            new MoneyRoundingPolicy('discounting.half_up', MoneyRoundingMode::HalfUp, MoneyRoundingContext::Discounting, description: 'Discount display/value derivation may opt into half-up rounding.'),
            new MoneyRoundingPolicy('subscription.reject', MoneyRoundingMode::Reject, MoneyRoundingContext::Subscription, description: 'Recurring prices should be stored with exact currency precision.'),
            new MoneyRoundingPolicy('formatting.half_up', MoneyRoundingMode::HalfUp, MoneyRoundingContext::Formatting, description: 'Formatting-only projections may round for display.'),
            new MoneyRoundingPolicy('cash.down', MoneyRoundingMode::Down, MoneyRoundingContext::Cash, 1, 'Cash policy placeholder for explicit cash rounding workflows.'),
        ];

        $indexed = [];
        foreach ($policies as $policy) {
            $indexed[$policy->getName()] = $policy;
        }

        return $indexed;
    }
}
