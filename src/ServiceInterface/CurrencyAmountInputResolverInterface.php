<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyAmountResolutionDTO;

/**
 * Entry-point contract for neighboring components that need canonical money
 * normalization without coupling to Currencing Doctrine entities.
 */
interface CurrencyAmountInputResolverInterface
{
    public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO;
}
