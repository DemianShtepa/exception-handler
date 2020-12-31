<?php

declare(strict_types=1);

namespace App\Domain\Services\User\Interfaces;

use App\Domain\ValueObjects\User\CleanPassword;

interface PasswordHasher
{
    public function hash(CleanPassword $cleanPassword): string;

    public function check(CleanPassword $cleanPassword, string $hashedPassword): bool;
}
