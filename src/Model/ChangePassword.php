<?php

declare(strict_types=1);

namespace App\Model;

use App\Validator\Constraint\ValidPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePassword
{
    /** @var string  */
    #[UserPassword]
    private string $currentPassword;

    /** @var string  */
    #[ValidPassword]
    private string $newPassword;

    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(string $currentPassword): void
    {
        $this->currentPassword = $currentPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }
}
