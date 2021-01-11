<?php

declare(strict_types=1);

namespace App\Domain\Services\User\Interfaces;

interface PasswordResetter
{
    public function resetPassword(string $token, string $password): void;
}
