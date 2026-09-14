<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service\Http\Currency;

use App\Currencing\DTO\CurrencyAmountDTO;
use App\Currencing\DTO\CurrencyAmountInputDTO;
use App\Currencing\DTO\CurrencyAmountResolutionDTO;
use App\Currencing\DTO\CurrencyDisplayDTO;
use App\Currencing\DTO\CurrencyRoundingPolicyDTO;
use App\Currencing\Enum\CurrencyRoundingContext;
use App\Currencing\Enum\CurrencyRoundingMode;
use App\Currencing\Service\Http\Currency\CurrencyNormalizeHttpService;
use App\Currencing\ServiceInterface\CurrencyAmountInputResolverInterface;
use PHPUnit\Framework\Attributes\DataProvider;
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
                throw new \LogicException('Resolver should not be called for invalid payload.');
            }
        };

        $controller = new CurrencyNormalizeHttpService($resolver);
        $response = $controller(new Request(content: '{}'));

        self::assertSame(400, $response->getStatusCode());
    }

    public function testItAcceptsFormPayloadAndReturnsRoundingPolicyMetadata(): void
    {
        $resolver = new class implements CurrencyAmountInputResolverInterface {
            public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO
            {
                return new CurrencyAmountResolutionDTO(
                    $input,
                    new CurrencyAmountDTO(1235, 'USD'),
                    new CurrencyDisplayDTO('$12.35', 1235, 'USD', '12.35', 'en_US'),
                    2,
                    new CurrencyRoundingPolicyDTO(
                        'cash.down',
                        CurrencyRoundingMode::Down,
                        CurrencyRoundingContext::Cash,
                        5,
                        'Cash rounding.',
                    ),
                );
            }
        };

        $request = new Request(request: [
            'amount' => '12.349',
            'currencyCode' => 'USD',
            'roundingMode' => 'down',
            'roundingContext' => 'cash',
            'sourceComponent' => 'ordering',
            'sourceReference' => '',
            'locale' => 'en_US',
            'roundingPolicyName' => 'cash.down',
        ]);

        $response = (new CurrencyNormalizeHttpService($resolver))($request);
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('cash.down', $payload['item']['roundingPolicy']['nameEntity']);
        self::assertSame('down', $payload['item']['roundingPolicy']['roundingMode']);
        self::assertSame('cash', $payload['item']['roundingPolicy']['context']);
        self::assertSame(5, $payload['item']['roundingPolicy']['cashIncrementMinorUnits']);
    }

    #[DataProvider('invalidPayloadProvider')]
    public function testItRejectsInvalidNormalizationPayloads(Request $request, string $expectedErrorFragment): void
    {
        $resolver = new class implements CurrencyAmountInputResolverInterface {
            public function resolve(CurrencyAmountInputDTO $input): CurrencyAmountResolutionDTO
            {
                throw new \LogicException('Resolver should not be called for invalid payload.');
            }
        };

        $response = (new CurrencyNormalizeHttpService($resolver))($request);
        $payload = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(400, $response->getStatusCode());
        self::assertStringContainsString($expectedErrorFragment, $payload['error']);
    }

    /** @return iterable<string, array{Request, string}> */
    public static function invalidPayloadProvider(): iterable
    {
        yield 'invalid json' => [new Request(content: '['), 'valid JSON object'];
        yield 'currency code non-string' => [new Request(content: '{"amount":"1.00","currencyCode":123}'), 'currencyCode must be a string'];
        yield 'currency code empty' => [new Request(content: '{"amount":"1.00","currencyCode":"  "}'), 'currencyCode must not be empty'];
        yield 'optional field non-string' => [new Request(content: '{"amount":"1.00","currencyCode":"USD","sourceComponent":[]}'), 'Optional string payload fields'];
        yield 'rounding mode non-string' => [new Request(content: '{"amount":"1.00","currencyCode":"USD","roundingMode":1}'), 'roundingMode must be a string'];
        yield 'rounding mode unsupported' => [new Request(content: '{"amount":"1.00","currencyCode":"USD","roundingMode":"bankers"}'), 'Unsupported roundingMode'];
        yield 'rounding context non-string' => [new Request(content: '{"amount":"1.00","currencyCode":"USD","roundingContext":1}'), 'roundingContext must be a string'];
        yield 'rounding context unsupported' => [new Request(content: '{"amount":"1.00","currencyCode":"USD","roundingContext":"unknown"}'), 'Unsupported roundingContext'];
    }
}
