<?php

declare(strict_types=1);

namespace App\ServiceInterface\Currency;

use App\Dto\Currency\CurrencyChoiceDTO;

interface CurrencyChoiceProviderInterface
{
    /**
     * @return list<CurrencyChoiceDTO>
     */
    public function choices(?string $locale = null): array;

    /**
     * @return array<string,string>
     */
    public function formChoices(?string $locale = null): array;
}
