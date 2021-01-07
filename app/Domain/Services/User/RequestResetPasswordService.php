<?php

declare(strict_types=1);

namespace App\Domain\Services\User;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Events\Interfaces\EventDispatcher;
use App\Domain\Events\ResetPasswordRequested;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestNotFound;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\ValueObjects\User\Email;
use DateInterval;
use DateTimeImmutable;

final class RequestResetPasswordService
{
    private UserRepository $userRepository;

    private ResetPasswordRequestRepository $resetPasswordRequestRepository;

    private TokenGenerator $tokenGenerator;

    private EventDispatcher $eventDispatcher;

    public function __construct(
        UserRepository $userRepository,
        ResetPasswordRequestRepository $resetPasswordRequestRepository,
        TokenGenerator $tokenGenerator,
        EventDispatcher $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function requestResetPassword(Email $email): ResetPasswordRequest
    {
        $user = $this->userRepository->getByEmail($email);

        try {
            $request = $this->resetPasswordRequestRepository->getByUser($user);

            $request = $this->processExistedRequest($request);
        } catch (ResetPasswordRequestNotFound $exception) {
            $request = $this->createNewRequest($user);
        }

        $this->eventDispatcher->dispatch(new ResetPasswordRequested($email->getValue(), $request->getToken()));

        return $request;
    }

    private function createNewRequest(User $user): ResetPasswordRequest
    {
        $request = new ResetPasswordRequest(
            $user,
            $this->tokenGenerator->generate(),
            (new DateTimeImmutable())->add(new DateInterval('PT1H'))
        );
        $this->resetPasswordRequestRepository->save($request);

        return $request;
    }

    private function processExistedRequest(ResetPasswordRequest $request): ResetPasswordRequest
    {
        if ($request->isExpiredComparedTo(new DateTimeImmutable())) {
            $request->setToken($this->tokenGenerator->generate());
            $request->setExpiresAt((new DateTimeImmutable())->add(new DateInterval('PT1H')));

            $this->resetPasswordRequestRepository->save($request);
        }

        return $request;
    }
}
