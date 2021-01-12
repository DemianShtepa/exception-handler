<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use App\Domain\Entities\Exception;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\Status\StatusException;
use App\Domain\Repositories\ExceptionRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\Exception\ExceptionStatusManager;
use App\Domain\ValueObjects\Exception\Name;
use App\Domain\ValueObjects\Exception\Stacktrace;
use DateTimeImmutable;
use Tests\TestCase;

class ExceptionStatusManagerTest extends TestCase
{
    private ExceptionRepository $exceptionRepository;
    private UserRepository $userRepository;
    private ExceptionStatusManager $exceptionStatusManager;

    public function setUp(): void
    {
        $this->exceptionRepository = $this->createMock(ExceptionRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    public function testSuccessSetToHandledFromUnhandled(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);
        $returnedException = $this->exceptionStatusManager
            ->setToHandled($user, 1);

        $this->assertSame('handled', $returnedException->getStatus()->getName());
        $this->assertSame($user, $returnedException->getAssignedUser());
    }

    public function testFailedSetToSolvedFromUnhandled(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);

        $this->expectException(StatusException::class);

        $this->exceptionStatusManager->setToSolved($user, 1);
    }

    public function testFailedSetToUnhandledFromUnhandled(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);

        $this->expectException(StatusException::class);

        $this->exceptionStatusManager->setToUnhandled($user, 1);
    }

    public function testSuccessSetToUnHandledFromHandled(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);
        $this->exceptionStatusManager
            ->setToHandled($user, 1);
        $returnedException = $this->exceptionStatusManager
            ->setToUnhandled($user, 1);

        $this->assertSame('unhandled', $returnedException->getStatus()->getName());
        $this->assertNull($returnedException->getAssignedUser());
    }

    public function testFailedSetToHandledFromHandled(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);

        $this->expectException(StatusException::class);

        $this->exceptionStatusManager->setToHandled($user, 1);
        $this->exceptionStatusManager->setToHandled($user, 1);
    }

    public function testSuccessSetToSolvedFromHandled(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);
        $this->exceptionStatusManager
            ->setToHandled($user, 1);
        $returnedException = $this->exceptionStatusManager
            ->setToSolved($user, 1);

        $this->assertSame('solved', $returnedException->getStatus()->getName());
    }

    public function testFailedSetToUnhandledFromSolved(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);

        $this->expectException(StatusException::class);

        $this->exceptionStatusManager->setToHandled($user, 1);
        $this->exceptionStatusManager->setToSolved($user, 1);
        $this->exceptionStatusManager->setToUnhandled($user, 1);
    }

    public function testFailedSetToSolveddFromSolved(): void
    {
        $exception = new Exception(
            new Name('name'),
            new Stacktrace('stacktrace'),
            new DateTimeImmutable(),
            $this->createMock(VirtualProject::class)
        );
        $user = $this->createMock(User::class);

        $this->exceptionRepository->method('getById')->willReturn($exception);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->exceptionStatusManager = new ExceptionStatusManager($this->exceptionRepository, $this->userRepository);

        $this->expectException(StatusException::class);

        $this->exceptionStatusManager->setToHandled($user, 1);
        $this->exceptionStatusManager->setToSolved($user, 1);
        $this->exceptionStatusManager->setToSolved($user, 1);
    }
}
