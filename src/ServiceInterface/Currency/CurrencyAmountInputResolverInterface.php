<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyAmountInputDTO;
use App\Dto\Currency\CurrencyAmountResolutionDTO;

/**
 * Entry-point contract for neighboring components that need canonical money
 * normalization without coupling to Currencing Doctrine entities.
 */
interface CurrencyAmountInputResolverInterface
{
    public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO;
}
