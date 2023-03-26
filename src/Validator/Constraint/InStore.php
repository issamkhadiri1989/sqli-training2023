<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
/**
 * @Annotation
 */
class InStore extends Constraint
{
    public string $message = 'You cannot order this product because the quantity requested exceeds the max of {{ max }}';

    public function getTargets(): string
    {
//        return parent::getTargets();
        return self::CLASS_CONSTRAINT;
    }
}
