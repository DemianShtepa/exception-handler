<?php

declare(strict_types=1);

namespace App\Domain\Services\User;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestNotFound;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\Transcation\Transaction;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use DateInterval;
use DateTimeImmutable;

final class ResetPasswordService
{
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;
    private TokenGenerator $tokenGenerator;
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private Transaction $transaction;

    public function __construct(
        ResetPasswordRequestRepository $resetPasswordRequestRepository,
        TokenGenerator $tokenGenerator,
        UserRepository $userRepository,
        PasswordHasher $passwordHasher,
        Transaction $transaction
    ) {
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->transaction = $transaction;
    }

    public function requestResetPassword(Email $email): ResetPasswordRequest
    {
        $user = $this->userRepository->getByEmail($email);

        try {
            $request = $this->resetPasswordRequestRepository->getByUser($user);

            return $this->processExistedRequest($request);
        } catch (ResetPasswordRequestNotFound $exception) {
            return $this->createNewRequest($user);
        }
    }

    public function resetPassword(string $token, CleanPassword $cleanPassword): void
    {
        $request = $this->resetPasswordRequestRepository->getByToken($token);
        $user = $request->getUser();

        $this->transaction->execute(function () use ($user, $cleanPassword, $request) {
            $user->setPassword($this->passwordHasher->hash($cleanPassword));
            $this->userRepository->persist($user);
            $this->userRepository->flush();

            $this->resetPasswordRequestRepository->remove($request);
            $this->resetPasswordRequestRepository->flush();
        });
    }

    private function createNewRequest(User $user): ResetPasswordRequest
    {
        $request = new ResetPasswordRequest(
            $user,
            $this->tokenGenerator->generate(),
            (new DateTimeImmutable())->add(new DateInterval('PT1H'))
        );
        $this->resetPasswordRequestRepository->persist($request);
        $this->resetPasswordRequestRepository->flush();

        return $request;
    }

    private function processExistedRequest(ResetPasswordRequest $request): ResetPasswordRequest
    {
        if ($request->isExpiredComparedTo(new DateTimeImmutable())) {
            $request->setToken($this->tokenGenerator->generate());
            $request->setExpiresAt((new DateTimeImmutable())->add(new DateInterval('PT1H')));

            $this->resetPasswordRequestRepository->persist($request);
            $this->resetPasswordRequestRepository->flush();
        }

        return $request;
    }
}
