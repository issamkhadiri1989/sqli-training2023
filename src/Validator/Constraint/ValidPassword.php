<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
/**
 * @Annotation
 */
class ValidPassword extends Constraint
{
    public string $message = 'The password you have entered is not valid. It should have at least 8 chars long, 1 upper and 1 lower.';
}
