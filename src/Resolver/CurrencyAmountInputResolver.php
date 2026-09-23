<?php

declare(strict_types=1);

namespace App\Currencing\Resolver;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyAmountResolutionDTO;
use App\Currencing\ServiceInterface\CurrencyAmountInputResolverInterface;
use App\Currencing\ServiceInterface\CurrencyAmountNormalizerInterface;
use App\Currencing\ServiceInterface\CurrencyDisplayFormatterInterface;
use App\Currencing\ServiceInterface\CurrencyPrecisionResolverInterface;
use App\Currencing\ServiceInterface\CurrencyRoundingPolicyResolverInterface;

final class CurrencyAmountInputResolver implements CurrencyAmountInputResolverInterface
{
    public function __construct(
        private readonly CurrencyAmountNormalizerInterface $moneyAmountNormalizer,
        private readonly CurrencyDisplayFormatterInterface $moneyDisplayFormatter,
        private readonly CurrencyPrecisionResolverInterface $currencyPrecisionResolver,
        private readonly CurrencyRoundingPolicyResolverInterface $moneyRoundingPolicyResolver,
    ) {
    }

    public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO
    {
        $roundingPolicy = $this->moneyRoundingPolicyResolver->resolveForInput($input);

        $moneyAmount = $this->moneyAmountNormalizer->normalize(
            $input->getAmount(),
            $input->getCurrencyCode(),
            $roundingPolicy->getRoundingMode(),
        );

        return new CurrencyAmountResolutionDTO(
            $input,
            $moneyAmount,
            $this->moneyDisplayFormatter->format($moneyAmount, $input->getLocale()),
            $this->currencyPrecisionResolver->minorUnitFor($moneyAmount->getCurrencyCode()),
            $roundingPolicy,
        );
    }
}
