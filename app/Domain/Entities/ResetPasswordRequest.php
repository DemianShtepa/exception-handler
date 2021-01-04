<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use DateTimeImmutable;

class ResetPasswordRequest
{
    private User $user;
    private string $token;
    private DateTimeImmutable $expiresAt;

    public function __construct(User $user, string $token, DateTimeImmutable $expiresAt)
    {
        $this->user = $user;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public function isExpiredComparedTo(DateTimeImmutable $comparedDate): bool
    {
        return $this->expiresAt < $comparedDate;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setExpiresAt(DateTimeImmutable $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
