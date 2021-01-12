<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Exception\Name;
use App\Domain\ValueObjects\Exception\Stacktrace;
use App\Domain\ValueObjects\Status\Status;
use App\Domain\ValueObjects\Status\UnhandledStatus;
use DateTimeImmutable;

class Exception
{
    private int $id;
    private Status $status;
    private Name $name;
    private Stacktrace $stacktrace;
    private DateTimeImmutable $createdAt;
    private ?User $assignedUser;
    private VirtualProject $virtualProject;

    public function __construct(
        Name $name,
        Stacktrace $stacktrace,
        DateTimeImmutable $createdAt,
        VirtualProject $virtualProject
    ) {
        $this->name = $name;
        $this->stacktrace = $stacktrace;
        $this->createdAt = $createdAt;
        $this->virtualProject = $virtualProject;
        $this->assignedUser = null;
        $this->status = new UnhandledStatus();
    }

    public function setToUnhandled(): void
    {
        $this->status = $this->status->setToUnhandled();
    }

    public function setToHandled(): void
    {
        $this->status = $this->status->setToHandled();
    }

    public function setToSolved(): void
    {
        $this->status = $this->status->setToSolved();
    }

    public function getVirtualProject(): VirtualProject
    {
        return $this->virtualProject;
    }

    public function assignUser(?User $assignedUser): void
    {
        $this->assignedUser = $assignedUser;
    }

    public function getAssignedUser(): ?User
    {
        return $this->assignedUser;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getStacktrace(): Stacktrace
    {
        return $this->stacktrace;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
