<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Domain\Services\User\Interfaces\PasswordResetter as ResetPasswordServiceInterface;
use App\Domain\Services\User\PasswordResetter;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionalPasswordResetter implements ResetPasswordServiceInterface
{
    private EntityManagerInterface $entityManager;

    private PasswordResetter $resetPasswordService;

    public function __construct(EntityManagerInterface $entityManager, PasswordResetter $resetPasswordService)
    {
        $this->entityManager = $entityManager;
        $this->resetPasswordService = $resetPasswordService;
    }

    public function resetPassword(string $token, string $password): void
    {
        $this->entityManager->transactional(function () use ($token, $password) {
            $this->resetPasswordService->resetPassword($token, $password);
        });
    }
}
