<?php

declare(strict_types=1);

namespace App\Voter;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditProductVoter extends Voter
{
    public const EDIT = 'can_edit_product';
    public const ADD = 'can_add_product';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Product && self::EDIT === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        // $subject est Product
        /** @var Product $product */
        $product = $subject;

        $store = $product->getStore();

        $owner = $store->getOwner();

        // only the Owner can edit the given product
        return $owner === $user;
    }
}
