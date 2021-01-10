<?php

declare(strict_types=1);

namespace App\Domain\Services\User;

use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestExpiredException;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\ValueObjects\User\CleanPassword;
use DateTimeImmutable;

final class ResetPasswordService
{
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;

    public function __construct(
        ResetPasswordRequestRepository $resetPasswordRequestRepository,
        UserRepository $userRepository,
        PasswordHasher $passwordHasher
    ) {
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function resetPassword(string $token, string $cleanPassword): void
    {
        $cleanPassword = new CleanPassword($cleanPassword);
        $request = $this->resetPasswordRequestRepository->getByToken($token);
        if ($request->isExpiredComparedTo(new DateTimeImmutable())) {
            throw new ResetPasswordRequestExpiredException();
        }

        $user = $request->getUser();

        $user->setPassword($this->passwordHasher->hash($cleanPassword));
        $this->userRepository->save($user);

        $this->resetPasswordRequestRepository->remove($request);
    }
}
