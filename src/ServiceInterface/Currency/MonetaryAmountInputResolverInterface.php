<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Dto\Currency\MonetaryAmountResolution;

/**
 * Entry-point contract for neighboring components that need canonical money
 * normalization without coupling to Currencing Doctrine entities.
 */
interface MonetaryAmountInputResolverInterface
{
    public function resolve(MonetaryAmountInput $input): MonetaryAmountResolution;
}
