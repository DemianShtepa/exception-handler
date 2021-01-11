<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Domain\Services\User\Interfaces\PasswordResetter;

final class ResetPasswordService
{
    private PasswordResetter $passwordResetter;

    public function __construct(PasswordResetter $passwordResetter)
    {
        $this->passwordResetter = $passwordResetter;
    }

    public function resetPassword(string $token, string $password): void
    {
        $this->passwordResetter->resetPassword($token, $password);
    }
}
