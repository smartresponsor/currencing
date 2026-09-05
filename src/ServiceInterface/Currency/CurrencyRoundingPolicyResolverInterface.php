<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyAmountInputDTO;
use App\Dto\Currency\CurrencyRoundingPolicyDTO;
use App\Enum\Currency\CurrencyRoundingContext;

interface CurrencyRoundingPolicyResolverInterface
{
    public function resolveForInput(CurrencyAmountInputDTO $input): CurrencyRoundingPolicyDTO;

    public function resolveNamedPolicy(string $policyName): CurrencyRoundingPolicyDTO;

    public function resolveForContext(CurrencyRoundingContext $context): CurrencyRoundingPolicyDTO;
}
