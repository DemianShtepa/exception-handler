<?php

declare(strict_types=1);

namespace App\Domain\Services\VirtualProject;

use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\ValueObjects\VirtualProject\Name;
use DateTimeImmutable;

final class VirtualProjectService
{
    private VirtualProjectRepository $virtualProjectRepository;
    private TokenGenerator $tokenGenerator;

    public function __construct(VirtualProjectRepository $virtualProjectRepository, TokenGenerator $tokenGenerator)
    {
        $this->virtualProjectRepository = $virtualProjectRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function createVirtualProject(Name $name, User $user): VirtualProject
    {
        $project = new VirtualProject(
            $name,
            $this->tokenGenerator->generate(),
            $this->tokenGenerator->generate(),
            new DateTimeImmutable(),
            $user
        );

        $this->virtualProjectRepository->persist($project);
        $this->virtualProjectRepository->flush();

        return $project;
    }
}
