<?php

declare(strict_types=1);

namespace App\Form\Currency;

use App\Entity\Currency\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', CurrencyIsoCodeChoiceType::class)
            ->add('numericCode', TextType::class, ['required' => false])
            ->add('minorUnit', IntegerType::class)
            ->add('symbol', TextType::class, ['required' => false])
            ->add('displayName', TextType::class, ['required' => false])
            ->add('active', CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Currency::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'currency';
    }
}
