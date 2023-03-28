<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
/**
 * @Annotation
 */
class Phone extends Constraint
{
    public string $message = 'The phone number must be of type +212 999-99-99-99.';
}
