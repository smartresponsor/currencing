<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CurrencyConversionBoundaryFunctionalTest extends WebTestCase
{
    public function testConversionBoundaryIsAvailableThroughTheStandaloneRuntime(): void
    {
        $client = static::createClient();

        $client->request('GET', '/currencing/conversion/boundary');

        self::assertResponseIsSuccessful();

        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($payload);
        self::assertSame(
            'Exchanging may depend on Currencing; Currencing must not depend on Exchanging.',
            $payload['dependencyDirection'] ?? null,
        );
    }
}
