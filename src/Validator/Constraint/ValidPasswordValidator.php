<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * This validator is used to validate passwords. A password is considered valid if :
 *  - it contains at least 8 chars
 *  - it contains at least 1 upper case
 *  - if contains at least 1 lower case.
 */
class ValidPasswordValidator extends ConstraintValidator
{
    private const PATTERN = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';

    public function validate(mixed $value, Constraint $constraint)
    {
        // make sure that the validator has passed the correct constraint.
        if (!$constraint instanceof ValidPassword) {
            throw new UnexpectedTypeException($value, ValidPassword::class);
        }

        // because this validator only checks the pattern
        if (null === $value || '' === $value) {
            // let the validation process go to the next validation
            // we already have NotNull and NotBlank constraints so we don't have to check them here.
            return;
        }

        // make sure that the given value is not something else than a string
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // now that we know this constraint validator is a capable to check and the value is a string, let's code the logic
        if (\preg_match(self::PATTERN, $value) === 0) {
            // we must not throw an exception but wee need to build violations.
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
