<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use DateTimeImmutable;

class ApiToken
{
    private int $id;
    private ?User $user;
    private string $token;
    private DateTimeImmutable $expiresAt;

    public function __construct(string $token, DateTimeImmutable $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
