<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isEnabled() === false) {
            throw new CustomUserMessageAccountStatusException('Your account has been disabled or never been activated before');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Check if the account's expiration date is still further
    }
}
