<?php

declare(strict_types=1);

namespace App\Domain\Services\VirtualProject;

use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\ValueObjects\VirtualProject\Name;
use DateTimeImmutable;

final class VirtualProjectService
{
    private VirtualProjectRepository $virtualProjectRepository;
    private TokenGenerator $tokenGenerator;
    private UserRepository $userRepository;

    public function __construct(
        VirtualProjectRepository $virtualProjectRepository,
        TokenGenerator $tokenGenerator,
        UserRepository $userRepository
    ) {
        $this->virtualProjectRepository = $virtualProjectRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->userRepository = $userRepository;
    }

    public function createVirtualProject(string $name, User $user): VirtualProject
    {
        $name = new Name($name);
        $project = new VirtualProject(
            $name,
            $this->tokenGenerator->generate(),
            $this->tokenGenerator->generate(),
            new DateTimeImmutable(),
            $user
        );

        $this->virtualProjectRepository->save($project);

        return $project;
    }

    public function updateName(User $user, string $name, int $id): string
    {
        $project = $this->virtualProjectRepository->getById($id);

        $this->checkUserPermission($user, $project);

        $name = new Name($name);

        $project->setName($name);

        $this->virtualProjectRepository->save($project);

        return $name->getValue();
    }

    public function changeInviteToken(User $user, int $id): string
    {
        $project = $this->virtualProjectRepository->getById($id);

        $this->checkUserPermission($user, $project);

        $token = $this->tokenGenerator->generate();
        $project->setInviteToken($token);

        $this->virtualProjectRepository->save($project);

        return $token;
    }

    public function changePushToken(User $user, int $id): string
    {
        $project = $this->virtualProjectRepository->getById($id);

        $this->checkUserPermission($user, $project);

        $token = $this->tokenGenerator->generate();
        $project->setPushToken($token);

        $this->virtualProjectRepository->save($project);

        return $token;
    }

    private function checkUserPermission(User $user, VirtualProject $virtualProject): void
    {
        if ($user !== $virtualProject->getOwner()) {
            throw new ForbiddenException();
        }
    }
}
