<?php

declare(strict_types=1);

namespace App\Form\Currency;

use App\ServiceInterface\Currency\CurrencyChoiceProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CurrencyIsoCodeChoiceType extends AbstractType
{
    public function __construct(private readonly CurrencyChoiceProviderInterface $currencyChoiceProvider)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('currency_locale', null);
        $resolver->setAllowedTypes('currency_locale', ['null', 'string']);

        $resolver->setDefaults([
            'choices' => fn (Options $options): array => $this->currencyChoiceProvider->formChoices($options['currency_locale']),
            'placeholder' => 'currency.form.placeholder',
            'choice_translation_domain' => false,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'currency_iso_code_choice';
    }
}
