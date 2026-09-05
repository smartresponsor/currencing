<?php

declare(strict_types=1);

namespace App\Tests\Dto\Currency;

use App\Dto\Currency\CurrencyAmountDTO;
use App\Dto\Currency\CurrencyConversionIntentDTO;
use PHPUnit\Framework\TestCase;

final class CurrencyConversionIntentDTOTest extends TestCase
{
    public function testItNormalizesTargetCurrencyCode(): void
    {
        $intent = new CurrencyConversionIntentDTO(
            new CurrencyAmountDTO(1234, 'usd'),
            'eur',
            'Ordering',
            'corr-1',
        );

        self::assertSame('USD', $intent->sourceCode());
        self::assertSame('EUR', $intent->targetCode());
        self::assertSame('Ordering', $intent->consumerName);
        self::assertSame('corr-1', $intent->correlationId);
    }

    public function testItRejectsSameCurrencyConversionIntent(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CurrencyConversionIntentDTO(
            new CurrencyAmountDTO(1234, 'USD'),
            'usd',
        );
    }
}
