<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\VirtualProject\Name;
use DateTimeImmutable;

class VirtualProject
{
    private int $id;
    private Name $name;
    private string $pushToken;
    private string $inviteToken;
    private DateTimeImmutable $createdAt;
    private User $owner;

    public function __construct(
        Name $name,
        string $pushToken,
        string $inviteToken,
        DateTimeImmutable $createdAt,
        User $owner
    ) {
        $this->name = $name;
        $this->pushToken = $pushToken;
        $this->inviteToken = $inviteToken;
        $this->createdAt = $createdAt;
        $this->owner = $owner;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPushToken(): string
    {
        return $this->pushToken;
    }

    public function getInviteToken(): string
    {
        return $this->inviteToken;
    }
}
