<?php

declare(strict_types=1);

namespace App\Domain\Services\VirtualProject;

use App\Domain\Entities\User;
use App\Domain\Exceptions\User\UserAlreadySubscribedException;
use App\Domain\Exceptions\User\UserNotSubscribedException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;

final class UserSubscriptionManager
{
    private UserRepository $userRepository;

    private VirtualProjectRepository $virtualProjectRepository;

    public function __construct(UserRepository $userRepository, VirtualProjectRepository $virtualProjectRepository)
    {
        $this->userRepository = $userRepository;
        $this->virtualProjectRepository = $virtualProjectRepository;
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

    public function unsubscribe(User $user, int $id): User
    {
        $request = $this->virtualProjectRepository->getById($id);

        if (!$this->userRepository->isUserSubscribedTo($user, $request)) {
            throw new UserNotSubscribedException();
        }

        $user->unsubscribe($request);
        $this->userRepository->save($user);

        return $user;
    }
}
