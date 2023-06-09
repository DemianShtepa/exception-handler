<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestNotFound;

interface ResetPasswordRequestRepository
{
    /** @throws ResetPasswordRequestNotFound */
    public function getByToken(string $token): ResetPasswordRequest;

    /** @throws ResetPasswordRequestNotFound */
    public function getByUser(User $user): ResetPasswordRequest;

    public function save(ResetPasswordRequest $request): void;

    public function remove(ResetPasswordRequest $request): void;
}
