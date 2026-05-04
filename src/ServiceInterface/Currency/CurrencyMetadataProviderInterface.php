<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

interface CurrencyMetadataProviderInterface
{
    /**
     * @return array{code:string,numericCode:?string,minorUnit:int,symbol:?string,displayName:?string}
     */
    public function metadataFor(string $code, ?string $locale = null): array;

    /**
     * @return list<string>
     */
    public function knownCodes(): array;
}
