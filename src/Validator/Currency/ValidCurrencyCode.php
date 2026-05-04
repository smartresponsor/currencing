<?php

declare(strict_types=1);

namespace App\Validator\Currency;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER)]
final class ValidCurrencyCode extends Constraint
{
    public string $message = 'currency.code.invalid';
}
