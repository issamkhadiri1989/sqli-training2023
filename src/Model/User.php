<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Validator\Constraints as Assert;

#[HasLifecycleCallbacks]
class User
{
    #[Assert\NotNull, Assert\NotBlank] // validated by `Default` and `User` validation groups
    private string $firstName;

    #[Assert\NotNull, Assert\NotBlank] // validated by `Default` and `User` validation groups
    private string $lastName;

    #[Assert\GreaterThan(10, groups: ['specific_group'])] // validated only when `specific_group` validation group is specified
    private int $age;

    #[Assert\Valid] // tell symfony to validate also embedded object
    private Address $address;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    #[PrePersist]
    public function defineSomething()
    {
        $this->address = '00000';
    }
}
