<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Account
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher, private readonly UserRepository $repository)
    {
    }

    /**
     * Register new user.
     *
     * @param User $account
     *
     * @return void
     */
    public function registerSubscription(User $account): void
    {
        $account->setEnabled(false);
        // hash the password
        $plainPassword = $account->getUserPassword();
        $account->setPassword($this->hasher->hashPassword($account, $plainPassword));
        $this->repository->save($account, true);

        // probably dispatch here an event to email the admin.
    }

    /**
     * Updates the last connection time of the given user.
     *
     * @param User $user
     *
     * @return void
     */
    public function updateLastConnectionTime(User $user): void
    {
        $user->setLastConnectionTime(new \DateTimeImmutable());
        $this->repository->save($user, true);
    }
}
