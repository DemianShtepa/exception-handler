<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\VirtualProject\Name;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class VirtualProject
{
    private int $id;
    private Name $name;
    private string $pushToken;
    private string $inviteToken;
    private DateTimeImmutable $createdAt;
    private User $owner;
    private Collection $subscribers;
    private Collection $exceptions;

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
        $this->subscribers = new ArrayCollection();
        $this->exceptions = new ArrayCollection();
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function setPushToken(string $pushToken): void
    {
        $this->pushToken = $pushToken;
    }

    public function setInviteToken(string $inviteToken): void
    {
        $this->inviteToken = $inviteToken;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
