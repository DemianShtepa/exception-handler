<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Domain\Services\User\ResetPasswordService;
use App\Domain\ValueObjects\User\CleanPassword;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionalResetPasswordService
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
            $this->resetPasswordService->resetPassword($token, new CleanPassword($password));
        });
    }
}
