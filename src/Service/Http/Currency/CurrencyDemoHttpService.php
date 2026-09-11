<?php

declare(strict_types=1);

namespace App\Currencing\Service\Http\Currency;

use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\Enum\CurrencyRoundingContext;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\ServiceInterface\CurrencyAmountInputResolverInterface;
use App\Currencing\ServiceInterface\CurrencySelectorViewProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Renders a lightweight capability demo for the Currencing component.
 *
 * This controller is intentionally read/demo oriented. It proves currency selector output,
 * formatting, and normalization without introducing a heavy admin subsystem or leaking
 * Doctrine entities into the presentation layer.
 */
final class CurrencyDemoHttpService
{
    public function __construct(
        private readonly CurrencySelectorViewProviderInterface $selectorViewProvider,
        private readonly CurrencyAmountInputResolverInterface $currencyAmountInputResolver,
        private readonly Environment $twig,
    ) {
    }

    public function __invoke(): Response
    {
        $selector = $this->selectorViewProvider->selector('USD');

        $examples = [
            $this->currencyAmountInputResolver->resolve(new CurrencyAmountInputDTO(amount: '12.34', currencyCode: 'USD', roundingContext: CurrencyRoundingContext::Ordering)),
            $this->currencyAmountInputResolver->resolve(new CurrencyAmountInputDTO(amount: '100', currencyCode: 'JPY', roundingContext: CurrencyRoundingContext::Paying)),
            $this->currencyAmountInputResolver->resolve(new CurrencyAmountInputDTO(amount: '19.9900', currencyCode: 'EUR', roundingMode: CurrencyRoundingMode::HalfUp, roundingPolicyName: 'formatting.half_up', roundingContext: CurrencyRoundingContext::Formatting)),
        ];

        return new Response($this->twig->render('@Currencing/currency/demo/index.html.twig', [
            'selector' => $selector,
            'examples' => $examples,
        ]));
    }
}
