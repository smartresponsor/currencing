<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Dto\Currency\MoneyRoundingPolicy;
use App\Enum\Currency\MoneyRoundingContext;

interface MoneyRoundingPolicyResolverInterface
{
    public function resolveForInput(MonetaryAmountInput $input): MoneyRoundingPolicy;

    public function resolveNamedPolicy(string $policyName): MoneyRoundingPolicy;

    public function resolveForContext(MoneyRoundingContext $context): MoneyRoundingPolicy;
}
