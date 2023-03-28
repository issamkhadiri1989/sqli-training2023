<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PhoneValidator extends ConstraintValidator
{
    private const PATTERN = '/^(\+212)\s((\d+){3})(\-(\d+){2}){3}$/';

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Phone) {
            throw new UnexpectedTypeException($constraint, Phone::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (\preg_match(self::PATTERN, $value) === 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
