<?php

declare(strict_types=1);

namespace Tests\Unit\User;

use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Events\Interfaces\EventDispatcher;
use App\Domain\Exceptions\ResetPasswordRequest\ResetPasswordRequestNotFound;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\User\RequestResetPasswordService;
use DateInterval;
use DateTimeImmutable;
use Tests\TestCase;

class RequestResetPasswordServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;
    private TokenGenerator $tokenGenerator;
    private EventDispatcher $eventDispatcher;
    private RequestResetPasswordService $requestResetPasswordService;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->resetPasswordRequestRepository = $this->createMock(ResetPasswordRequestRepository::class);
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
        $this->eventDispatcher = $this->createMock(EventDispatcher::class);
    }

    public function testSuccessResetPasswordCreation(): void
    {
        $this->resetPasswordRequestRepository->method('getByUser')->willThrowException(new ResetPasswordRequestNotFound());
        $this->tokenGenerator->method('generate')->willReturn('new-link');
        $this->requestResetPasswordService = new RequestResetPasswordService(
            $this->userRepository,
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->eventDispatcher
        );

        $returnedRequest = $this->requestResetPasswordService->requestResetPassword('some@mail.com');

        $this->assertEquals('new-link', $returnedRequest->getToken());
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
        $this->requestResetPasswordService = new RequestResetPasswordService(
            $this->userRepository,
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->eventDispatcher
        );

        $returnedRequest = $this->requestResetPasswordService->requestResetPassword('some@mail.com');

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
        $this->requestResetPasswordService = new RequestResetPasswordService(
            $this->userRepository,
            $this->resetPasswordRequestRepository,
            $this->tokenGenerator,
            $this->eventDispatcher
        );

        $returnedRequest = $this->requestResetPasswordService->requestResetPassword('some@mail.com');

        $this->assertEquals('new-link', $returnedRequest->getToken());
    }
}
