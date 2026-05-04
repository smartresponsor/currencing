<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\Dto\Currency\MonetaryAmountInput;
use App\Enum\Currency\MoneyRoundingContext;
use App\ServiceInterface\Currency\CurrencySelectorViewProviderInterface;
use App\ServiceInterface\Currency\MonetaryAmountInputResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Renders a lightweight capability demo for the Currencing component.
 *
 * This controller is intentionally read/demo oriented. It proves currency selector output,
 * formatting, and normalization without introducing a heavy admin subsystem or leaking
 * Doctrine entities into the presentation layer.
 */
final class CurrencyDemoController extends AbstractController
{
    public function __construct(
        private readonly CurrencySelectorViewProviderInterface $selectorViewProvider,
        private readonly MonetaryAmountInputResolverInterface $monetaryAmountInputResolver,
    ) {
    }

    #[Route('/currencing/demo', name: 'currencing_demo_index', methods: ['GET'])]
    public function __invoke(): Response
    {
        $selector = $this->selectorViewProvider->provideSelectorView('USD');

        $examples = [
            $this->monetaryAmountInputResolver->resolve(new MonetaryAmountInput('12.34', 'USD', MoneyRoundingContext::Ordering)),
            $this->monetaryAmountInputResolver->resolve(new MonetaryAmountInput('100', 'JPY', MoneyRoundingContext::Paying)),
            $this->monetaryAmountInputResolver->resolve(new MonetaryAmountInput('19.9900', 'EUR', MoneyRoundingContext::Formatting, 'formatting.half_up')),
        ];

        return $this->render('@Currencing/currency/demo/index.html.twig', [
            'selector' => $selector,
            'examples' => $examples,
        ]);
    }
}
