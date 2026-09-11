<?php

declare(strict_types=1);

namespace App\Currencing\Service\Http\Currency;

use App\Currencing\ServiceInterface\CurrencyMetadataViewProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Displays a read-only currency catalog preview.
 *
 * This is not a full back-office CRUD area. It is a component verification surface that
 * helps host applications confirm that metadata, fixtures, formatting, and selector data
 * are wired correctly.
 */
final class CurrencyAdminPreviewHttpService
{
    public function __construct(
        private readonly CurrencyMetadataViewProviderInterface $metadataViewProvider,
        private readonly Environment $twig,
    ) {
    }

    public function __invoke(): Response
    {
        return new Response($this->twig->render('@Currencing/currency/admin-preview/currencies.html.twig', [
            'currencies' => $this->metadataViewProvider->allViews(),
        ]));
    }
}
