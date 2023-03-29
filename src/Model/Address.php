<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Address
{
    #[Assert\NotNull(groups: ['User'])] // validated  only when the  validated group = `User` (the class name)
    #[Assert\NotBlank] // validated by the `Default` group
    private string $postalAddress;

    #[Assert\NotBlank(groups: ['specific_group'])] // validated only when `specific_group` validation group is specified
    private string $city;

    private string $country;

    public function getPostalAddress(): string
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(string $postalAddress): void
    {
        $this->postalAddress = $postalAddress;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}
