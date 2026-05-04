<?php

declare(strict_types=1);

namespace App\Tests\Controller\Currency;

use App\Controller\Currency\MoneyNormalizeController;
use App\Dto\Currency\MonetaryAmountInput;
use App\Dto\Currency\MonetaryAmountResolution;
use App\Dto\Currency\MoneyAmount;
use App\Dto\Currency\MoneyDisplay;
use App\ServiceInterface\Currency\MonetaryAmountInputResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class MoneyNormalizeControllerTest extends TestCase
{
    public function testItReturnsCanonicalMoneyNormalizationResponse(): void
    {
        $resolver = new class implements MonetaryAmountInputResolverInterface {
            public function resolve(MonetaryAmountInput $input): MonetaryAmountResolution
            {
                return new MonetaryAmountResolution(
                    $input,
                    new MoneyAmount(1234, 'USD'),
                    new MoneyDisplay('$12.34', 1234, 'USD', '12.34', 'en_US'),
                    2,
                );
            }
        };

        $controller = new MoneyNormalizeController($resolver);
        $response = $controller(new Request(content: json_encode([
            'amount' => '12.34',
            'currencyCode' => 'USD',
            'locale' => 'en_US',
        ], JSON_THROW_ON_ERROR)));

        self::assertSame(200, $response->getStatusCode());
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('currencing', $payload['component']);
        self::assertSame(1234, $payload['item']['minorUnits']);
        self::assertSame('USD', $payload['item']['currencyCode']);
        self::assertSame('$12.34', $payload['item']['formattedAmount']);
    }

    public function testItRejectsMissingRequiredPayloadFields(): void
    {
        $resolver = new class implements MonetaryAmountInputResolverInterface {
            public function resolve(MonetaryAmountInput $input): MonetaryAmountResolution
            {
                self::fail('Resolver should not be called for invalid payload.');
            }
        };

        $controller = new MoneyNormalizeController($resolver);
        $response = $controller(new Request(content: '{}'));

        self::assertSame(400, $response->getStatusCode());
    }
}
