<?php

declare(strict_types=1);

namespace App\Currencing\ServiceInterface;

use App\Currencing\DTO\CurrencyTemplateContextDTO;

interface CurrencyTemplateContextProviderInterface
{
    /**
     * Returns a bridge-safe output model for templates/UI composition.
     *
     * This is the outbound contract template composition components should depend on.
     */
    public function context(?string $selectedCode = null, ?string $locale = null): CurrencyTemplateContextDTO;
}
