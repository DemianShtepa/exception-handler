<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestExpiredException;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\Services\User\ResetPasswordService;
use Tests\TestCase;

final class ResetPasswordServiceTest extends TestCase
{
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;
    private ResetPasswordService $resetPasswordService;
    private PasswordHasher $passwordHasher;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->resetPasswordRequestRepository = $this->createMock(ResetPasswordRequestRepository::class);
        $this->passwordHasher = $this->createMock(PasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    public function testResetPasswordRequestExpired()
    {
        $request = $this->createMock(ResetPasswordRequest::class);
        $request->method('isExpiredComparedTo')->willReturn(true);
        $this->resetPasswordRequestRepository->method('getByToken')->willReturn($request);
        $this->resetPasswordService = new ResetPasswordService(
            $this->resetPasswordRequestRepository,
            $this->userRepository,
            $this->passwordHasher
        );

        $this->expectException(ResetPasswordRequestExpiredException::class);

        $this->resetPasswordService->resetPassword('some-token', 'some-password');
    }
}
