<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Service;

use App\Currencing\Provider\CurrencyChoiceProvider;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;
use PHPUnit\Framework\TestCase;

final class CurrencyChoiceProviderTest extends TestCase
{
    public function testBuildsFormChoicesWithoutDoctrineEntityLeakage(): void
    {
        $provider = new CurrencyChoiceProvider(new class implements CurrencyMetadataProviderInterface {
            public function metadataFor(string $code, ?string $locale = null): array
            {
                return match (strtoupper($code)) {
                    'USD' => ['code' => 'USD', 'numericCode' => '840', 'minorUnit' => 2, 'symbol' => '$', 'displayName' => 'US Dollar'],
                    'JPY' => ['code' => 'JPY', 'numericCode' => '392', 'minorUnit' => 0, 'symbol' => '¥', 'displayName' => 'Japanese Yen'],
                    default => ['code' => strtoupper($code), 'numericCode' => null, 'minorUnit' => 2, 'symbol' => null, 'displayName' => null],
                };
            }

            public function knownCodes(): array
            {
                return ['JPY', 'USD'];
            }
        });

        self::assertSame([
            'JPY — Japanese Yen — ¥' => 'JPY',
            'USD — US Dollar — $' => 'USD',
        ], $provider->formChoices());
    }
}
