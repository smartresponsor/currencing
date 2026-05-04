<?php

declare(strict_types=1);

namespace App\Validator\Currency;

use App\ServiceInterface\Currency\CurrencyCodeValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ValidCurrencyCodeValidator extends ConstraintValidator
{
    public function __construct(private readonly CurrencyCodeValidatorInterface $currencyCodeValidator)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidCurrencyCode) {
            throw new UnexpectedTypeException($constraint, ValidCurrencyCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$this->currencyCodeValidator->supports((string) $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
