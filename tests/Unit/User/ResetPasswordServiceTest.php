<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestExpiredException;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestNotFound;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\Transcation\Transaction;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\Services\User\ResetPasswordService;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use DateInterval;
use DateTimeImmutable;
use Tests\TestCase;

final class ResetPasswordServiceTest extends TestCase
{
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;
    private TokenGenerator $tokenGenerator;
    private ResetPasswordService $resetPasswordService;
    private PasswordHasher $passwordHasher;
    private UserRepository $userRepository;
    private Transaction $transaction;

    public function setUp(): void
    {
        $this->resetPasswordRequestRepository = $this->createMock(ResetPasswordRequestRepository::class);
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
        $this->passwordHasher = $this->createMock(PasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->transaction = $this->createMock(Transaction::class);
    }

    public function testSuccessResetPasswordCreation(): void
    {
        $this->resetPasswordRequestRepository->method('getByUser')->willThrowException(new ResetPasswordRequestNotFound());
        $this->tokenGenerator->method('generate')->willReturn('new-link');
        $this->resetPasswordService = new ResetPasswordService(
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->userRepository,
            $this->passwordHasher,
            $this->transaction
        );

        $returnedRequest = $this->resetPasswordService->requestResetPassword(new Email('some@mail.com'));

        $this->assertEquals('new-link', $returnedRequest->getToken());
    }

    public function testFailedResetPasswordOnExpiration(): void
    {
        $request = $this->createMock(ResetPasswordRequest::class);
        $request
            ->method('isExpiredComparedTo')
            ->willReturn(true);
        $this->resetPasswordRequestRepository->method('getByToken')->willReturn($request);
        $this->resetPasswordService = new ResetPasswordService(
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->userRepository,
            $this->passwordHasher,
            $this->transaction
        );

        $this->expectException(ResetPasswordRequestExpiredException::class);

        $this->resetPasswordService->resetPassword('token', new CleanPassword('password'));
    }

    public function testReturnOldRequestIfNotExpired(): void
    {
        $request = new ResetPasswordRequest(
            $this->createMock(User::class),
            'link',
            (new DateTimeImmutable())->add(new DateInterval('PT1H'))
        );
        $this->resetPasswordRequestRepository->method('getByUser')->willReturn($request);
        $this->tokenGenerator->method('generate')->willReturn('new-link');
        $this->resetPasswordService = new ResetPasswordService(
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->userRepository,
            $this->passwordHasher,
            $this->transaction
        );

        $returnedRequest = $this->resetPasswordService->requestResetPassword(new Email('some@mail.com'));

        $this->assertEquals('link', $returnedRequest->getToken());
    }

    public function testReturnNewRequestIfExpired(): void
    {
        $request = new ResetPasswordRequest(
            $this->createMock(User::class),
            'link',
            (new DateTimeImmutable())->sub(new DateInterval('PT1H'))
        );
        $this->resetPasswordRequestRepository->method('getByUser')->willReturn($request);
        $this->tokenGenerator->method('generate')->willReturn('new-link');
        $this->resetPasswordService = new ResetPasswordService(
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->userRepository,
            $this->passwordHasher,
            $this->transaction
        );

        $returnedRequest = $this->resetPasswordService->requestResetPassword(new Email('some@mail.com'));

        $this->assertEquals('new-link', $returnedRequest->getToken());
    }
}
