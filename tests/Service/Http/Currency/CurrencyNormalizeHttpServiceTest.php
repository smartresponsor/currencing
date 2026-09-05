<?php

declare(strict_types=1);

namespace App\Tests\Service\Http\Currency;

use App\Dto\Currency\CurrencyAmountDTO;
use App\Dto\Currency\CurrencyAmountInputDTO;
use App\Dto\Currency\CurrencyAmountResolutionDTO;
use App\Dto\Currency\CurrencyDisplayDTO;
use App\Service\Http\Currency\CurrencyNormalizeHttpService;
use App\ServiceInterface\Currency\CurrencyAmountInputResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CurrencyNormalizeHttpServiceTest extends TestCase
{
    public function testItReturnsCanonicalMoneyNormalizationResponse(): void
    {
        $resolver = new class implements CurrencyAmountInputResolverInterface {
            public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO
            {
                return new CurrencyAmountResolutionDTO(
                    $input,
                    new CurrencyAmountDTO(1234, 'USD'),
                    new CurrencyDisplayDTO('$12.34', 1234, 'USD', '12.34', 'en_US'),
                    2,
                );
            }
        };

        $controller = new CurrencyNormalizeHttpService($resolver);
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
        $resolver = new class implements CurrencyAmountInputResolverInterface {
            public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO
            {
                self::fail('Resolver should not be called for invalid payload.');
            }
        };

        $controller = new CurrencyNormalizeHttpService($resolver);
        $response = $controller(new Request(content: '{}'));

        self::assertSame(400, $response->getStatusCode());
    }
}
