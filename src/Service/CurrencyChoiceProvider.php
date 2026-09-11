<?php

declare(strict_types=1);

namespace App\Currencing\Service;

use App\Currencing\DTO\CurrencyChoiceDTO;
use App\Currencing\ServiceInterface\CurrencyChoiceProviderInterface;
use App\Currencing\ServiceInterface\CurrencyMetadataProviderInterface;

final class CurrencyChoiceProvider implements CurrencyChoiceProviderInterface
{
    public function __construct(private readonly CurrencyMetadataProviderInterface $currencyMetadataProvider)
    {
    }

    public function choices(?string $locale = null): array
    {
        $choices = [];

        foreach ($this->currencyMetadataProvider->knownCodes() as $code) {
            $metadata = $this->currencyMetadataProvider->metadataFor($code, $locale);
            $label = $this->buildLabel($metadata['code'], $metadata['displayName'], $metadata['symbol']);

            $choices[] = new CurrencyChoiceDTO(
                $metadata['code'],
                $label,
                $metadata['symbol'],
                $metadata['minorUnit'],
            );
        }

        usort(
            $choices,
            static fn (CurrencyChoiceDTO $left, CurrencyChoiceDTO $right): int => $left->getCode() <=> $right->getCode(),
        );

        return $choices;
    }

    public function formChoices(?string $locale = null): array
    {
        $formChoices = [];

        foreach ($this->choices($locale) as $choice) {
            $formChoices[$choice->getLabel()] = $choice->getCode();
        }

        return $formChoices;
    }

    private function buildLabel(string $code, ?string $displayName, ?string $symbol): string
    {
        $parts = [$code];

        if (null !== $displayName && '' !== trim($displayName)) {
            $parts[] = $displayName;
        }

        if (null !== $symbol && '' !== trim($symbol) && $symbol !== $code) {
            $parts[] = $symbol;
        }

        return implode(' — ', $parts);
    }
}
