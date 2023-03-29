<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Admin extends User
{
    #[Assert\Length(min: 6, max: 10)] // validated by the `Default` and `Admin` group
    #[Assert\NotNull] // validated by the `Default`  and `Admin` group
    private string $secret;

    #[Assert\NotNull(groups: ['specific_group'])] // validated only when `specific_group` validation group is specified
    private string $public;

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function getPublic(): string
    {
        return $this->public;
    }

    public function setPublic(string $public): void
    {
        $this->public = $public;
    }
}
