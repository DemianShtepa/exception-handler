<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Domain\Services\User\Interfaces\ResetPasswordService as ResetPasswordServiceInterface;
use App\Domain\Services\User\ResetPasswordService;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionalResetPasswordService implements ResetPasswordServiceInterface
{
    private EntityManagerInterface $entityManager;

    private ResetPasswordService $resetPasswordService;

    public function __construct(EntityManagerInterface $entityManager, ResetPasswordService $resetPasswordService)
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
