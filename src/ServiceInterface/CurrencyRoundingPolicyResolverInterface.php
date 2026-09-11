<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyRoundingPolicyDTO;
use App\Currencing\Enum\CurrencyRoundingContext;

interface CurrencyRoundingPolicyResolverInterface
{
    public function resolveForInput(CurrencyAmountInputDTO $input): CurrencyRoundingPolicyDTO;

    public function resolveNamedPolicy(string $policyName): CurrencyRoundingPolicyDTO;

    public function resolveForContext(CurrencyRoundingContext $context): CurrencyRoundingPolicyDTO;
}
