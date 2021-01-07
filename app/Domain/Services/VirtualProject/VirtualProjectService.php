<?php

declare(strict_types=1);

namespace App\Domain\Services\VirtualProject;

use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\User\UserAlreadySubscribedException;
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
    )
    {
        $this->virtualProjectRepository = $virtualProjectRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->userRepository = $userRepository;
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

        $this->virtualProjectRepository->save($project);

        return $project;
    }

    public function subscribe(User $user, string $inviteToken): User
    {
        $request = $this->virtualProjectRepository->getByInviteToken($inviteToken);

        if ($this->userRepository->isUserSubscribedTo($user, $request)) {
            throw new UserAlreadySubscribedException();
        }

        $user->subscribe($request);
        $this->userRepository->save($user);

        return $user;
    }
}
