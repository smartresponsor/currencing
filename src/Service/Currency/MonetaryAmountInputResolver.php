<?php

declare(strict_types=1);

namespace App\Service\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Dto\Currency\MonetaryAmountResolution;
use App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface;
use App\ServiceInterface\Currency\MoneyAmountNormalizerInterface;
use App\ServiceInterface\Currency\MoneyDisplayFormatterInterface;
use App\ServiceInterface\Currency\MoneyRoundingPolicyResolverInterface;
use App\ServiceInterface\Currency\MonetaryAmountInputResolverInterface;

final class MonetaryAmountInputResolver implements MonetaryAmountInputResolverInterface
{
    public function __construct(
        private readonly MoneyAmountNormalizerInterface $moneyAmountNormalizer,
        private readonly MoneyDisplayFormatterInterface $moneyDisplayFormatter,
        private readonly CurrencyPrecisionResolverInterface $currencyPrecisionResolver,
        private readonly MoneyRoundingPolicyResolverInterface $moneyRoundingPolicyResolver,
    ) {
    }

    public function resolve(MonetaryAmountInput $input): MonetaryAmountResolution
    {
        $roundingPolicy = $this->moneyRoundingPolicyResolver->resolveForInput($input);

        $moneyAmount = $this->moneyAmountNormalizer->normalize(
            $input->getAmount(),
            $input->getCurrencyCode(),
            $roundingPolicy->getRoundingMode(),
        );

        return new MonetaryAmountResolution(
            $input,
            $moneyAmount,
            $this->moneyDisplayFormatter->format($moneyAmount, $input->getLocale()),
            $this->currencyPrecisionResolver->minorUnitFor($moneyAmount->getCurrencyCode()),
            $roundingPolicy,
        );
    }
}
