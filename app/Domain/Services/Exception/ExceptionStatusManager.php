<?php

declare(strict_types=1);

namespace App\Domain\Services\Exception;

use App\Domain\Entities\Exception;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Repositories\ExceptionRepository;
use App\Domain\Repositories\UserRepository;

final class ExceptionStatusManager
{
    private ExceptionRepository $exceptionRepository;

    private UserRepository $userRepository;

    public function __construct(ExceptionRepository $exceptionRepository, UserRepository $userRepository)
    {
        $this->exceptionRepository = $exceptionRepository;
        $this->userRepository = $userRepository;
    }

    public function setToUnhandled(User $user, int $exceptionId): Exception
    {
        $exception = $this->exceptionRepository->getById($exceptionId);
        $project = $exception->getVirtualProject();
        $this->checkUserPermission($user, $project);
        if ($assignedUser = $exception->getAssignedUser()) {
            $this->checkAssignedUser($user, $assignedUser);
        }

        $exception->setToUnhandled();
        $exception->assignUser(null);

        $this->exceptionRepository->save($exception);

        return $exception;
    }

    public function setToHandled(User $user, int $exceptionId): Exception
    {
        $exception = $this->exceptionRepository->getById($exceptionId);
        $project = $exception->getVirtualProject();
        $this->checkUserPermission($user, $project);
        if ($assignedUser = $exception->getAssignedUser()) {
            $this->checkAssignedUser($user, $assignedUser);
        }

        $exception->setToHandled();
        $exception->assignUser($user);

        $this->exceptionRepository->save($exception);

        return $exception;
    }

    public function setToSolved(User $user, int $exceptionId): Exception
    {
        $exception = $this->exceptionRepository->getById($exceptionId);
        $project = $exception->getVirtualProject();
        $this->checkUserPermission($user, $project);
        if ($assignedUser = $exception->getAssignedUser()) {
            $this->checkAssignedUser($user, $assignedUser);
        }

        $exception->setToSolved();

        $this->exceptionRepository->save($exception);

        return $exception;
    }

    private function checkUserPermission(User $user, VirtualProject $virtualProject): void
    {
        if (!$this->userRepository->isUserSubscribedTo($user, $virtualProject)) {
            throw new ForbiddenException();
        }
    }

    private function checkAssignedUser(User $user, User $assignedUser): void
    {
        if ($assignedUser !== $user) {
            throw new ForbiddenException();
        }
    }
}
