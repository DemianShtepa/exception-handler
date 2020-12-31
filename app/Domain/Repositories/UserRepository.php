<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Exceptions\User\UserNotFoundException;
use App\Domain\ValueObjects\User\Email;

interface UserRepository
{
    /**
     * @throws UserNotFoundException
     */
    public function getByEmail(Email $email): User;

    public function hasByEmail(Email $email): bool;

    public function persist(User $user): void;

    public function flush(): void;
}
