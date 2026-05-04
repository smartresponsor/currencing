<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\ServiceInterface\Currency\CurrencyMetadataViewProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Displays a read-only currency catalog preview.
 *
 * This is not a full back-office CRUD area. It is a component verification surface that
 * helps host applications confirm that metadata, fixtures, formatting, and selector data
 * are wired correctly.
 */
final class CurrencyAdminPreviewController extends AbstractController
{
    public function __construct(
        private readonly CurrencyMetadataViewProviderInterface $metadataViewProvider,
    ) {
    }

    #[Route('/currencing/admin-preview/currencies', name: 'currencing_admin_preview_currencies', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('@Currencing/currency/admin-preview/currencies.html.twig', [
            'currencies' => $this->metadataViewProvider->provideActiveCurrencyMetadataViews(),
        ]);
    }
}
