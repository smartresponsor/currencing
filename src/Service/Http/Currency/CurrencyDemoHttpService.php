<?php

declare(strict_types=1);

namespace App\Service\Http\Currency;

use App\Dto\Currency\CurrencyAmountInputDTO;
use App\Enum\Currency\CurrencyRoundingContext;
use App\ServiceInterface\Currency\CurrencyAmountInputResolverInterface;
use App\ServiceInterface\Currency\CurrencySelectorViewProviderInterface;
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
            $this->currencyAmountInputResolver->resolve(new CurrencyAmountInputDTO('12.34', 'USD', CurrencyRoundingContext::Ordering)),
            $this->currencyAmountInputResolver->resolve(new CurrencyAmountInputDTO('100', 'JPY', CurrencyRoundingContext::Paying)),
            $this->currencyAmountInputResolver->resolve(new CurrencyAmountInputDTO('19.9900', 'EUR', CurrencyRoundingContext::Formatting, 'formatting.half_up')),
        ];

        return new Response($this->twig->render('@Currencing/currency/demo/index.html.twig', [
            'selector' => $selector,
            'examples' => $examples,
        ]));
    }
}
