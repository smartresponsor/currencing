<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\CurrencyAmountInputDTO;
use App\Dto\Currency\CurrencyAmountResolutionDTO;
use App\ServiceInterface\Currency\CurrencyAmountInputResolverInterface;
use App\ServiceInterface\Currency\CurrencyAmountNormalizerInterface;
use App\ServiceInterface\Currency\CurrencyDisplayFormatterInterface;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;
use App\ServiceInterface\Currency\CurrencyRoundingPolicyResolverInterface;

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
