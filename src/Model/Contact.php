<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * This class model is used to hold data of the Contact us form.
 */
class Contact
{
    #[Assert\NotNull, Assert\NotBlank]
    private string $name;

    #[Assert\Email, Assert\NotNull, Assert\NotBlank]
    private string $email;

    #[Assert\NotNull, Assert\NotBlank]
    private string $subject;

    #[Assert\NotNull, Assert\Length(min: 20, max: 100)]
    private string $message;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
