<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use App\Entity\CartItem;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class InStoreValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value instanceof CartItem) {
            throw new UnexpectedValueException($value, CartItem::class);
        }

        $product = $value->getProduct();
        $cartItem = $value->getCart()
            ->findCartItemFor($product);

        if (null !== $cartItem) {
            // To simplify life, let's say that we check only if the requested quantity is not higher than the maximum allowed
            $quantityInCart = $cartItem->getQuantity();
            $maxQuantityInStore = $product->getQuantity();

            if ($maxQuantityInStore < $quantityInCart) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ max }}', (string) $product->getQuantity())
                    ->atPath('quantity')
                    ->addViolation();
            }
        }
    }
}
