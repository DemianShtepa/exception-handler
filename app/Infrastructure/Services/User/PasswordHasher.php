<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Domain\Services\User\Interfaces\PasswordHasher as PasswordHasherInterface;
use App\Domain\ValueObjects\User\CleanPassword;

final class PasswordHasher implements PasswordHasherInterface
{
    public function hash(CleanPassword $cleanPassword): string
    {
        return (string) password_hash($cleanPassword->getValue(), PASSWORD_DEFAULT);
    }

    public function check(CleanPassword $cleanPassword, string $hashedPassword): bool
    {
        return password_verify($cleanPassword->getValue(), $hashedPassword);
    }
}
