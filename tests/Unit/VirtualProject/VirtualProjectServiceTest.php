<?php

declare(strict_types=1);

namespace Tests\Unit\VirtualProject;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Exceptions\User\UserAlreadySubscribedException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\VirtualProject\VirtualProjectService;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name as UserName;
use DateTimeImmutable;
use InvalidArgumentException;
use Tests\TestCase;

class VirtualProjectServiceTest extends TestCase
{
    private VirtualProjectRepository $virtualProjectRepository;
    private TokenGenerator $tokenGenerator;
    private VirtualProjectService $virtualProjectService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->virtualProjectRepository = $this->createMock(VirtualProjectRepository::class);
        $this->tokenGenerator = $this->createMock(TokenGenerator::class);
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    public function testSuccessProjectCreation(): void
    {
        $this->tokenGenerator->method('generate')->willReturn('token');
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $project = $this->virtualProjectService->createVirtualProject(
            'somename',
          $this->createMock(User::class)
        );

        $this->assertEquals('somename', $project->getName()->getValue());
        $this->assertEquals('token', $project->getInviteToken());
        $this->assertEquals('token', $project->getPushToken());
    }

    public function testUserAlreadySubscribed(): void
    {
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $this->expectException(UserAlreadySubscribedException::class);

        $this->virtualProjectService->subscribe(
            $this->createMock(User::class),
            'some-token'
        );
    }

    public function testSuccessUserSubscription(): void
    {
        $user = new User(
            new UserName('username'),
            new Email('some@mail.com'),
            'password',
            new ApiToken('token', new DateTimeImmutable())
        );
        $this->virtualProjectRepository->method('getByInviteToken')->willReturn($this->createMock(VirtualProject::class));
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $project = $this->virtualProjectService->subscribe(
            $user,
            'some-token'
        );

        $this->assertEquals(1, $user->getSubscriptions()->count());
    }

    public function testSuccessProjectNameChanging(): void
    {
        $user = $this->createMock(User::class);
        $project = $this->createMock(VirtualProject::class);
        $project->method('getOwner')->willReturn($user);
        $this->virtualProjectRepository->method('getById')->willReturn($project);
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $name = $this->virtualProjectService->updateName(
            $user,
            'some-name',
            1
        );

        $this->assertEquals('some-name', $name);
    }

    public function testPermissionDeniedOnNameUpdating(): void
    {
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $this->expectException(ForbiddenException::class);

        $name = $this->virtualProjectService->updateName(
            $this->createMock(User::class),
            'some-name',
            1
        );
    }

    public function testInvalidProjectName(): void
    {
        $user = $this->createMock(User::class);
        $project = $this->createMock(VirtualProject::class);
        $project->method('getOwner')->willReturn($user);
        $this->virtualProjectRepository->method('getById')->willReturn($project);
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $this->expectException(InvalidArgumentException::class);

        $name = $this->virtualProjectService->updateName(
            $user,
            'n',
            1
        );
    }

    public function testSuccessProjectPushTokenChanging(): void
    {
        $user = $this->createMock(User::class);
        $project = $this->createMock(VirtualProject::class);
        $project->method('getOwner')->willReturn($user);
        $this->virtualProjectRepository->method('getById')->willReturn($project);
        $this->tokenGenerator->method('generate')->willReturn('token');
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $token = $this->virtualProjectService->changePushToken(
            $user,
            1
        );

        $this->assertNotEmpty($token);
    }

    public function testPermissionDeniedOnPushTokenChanging(): void
    {
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $this->expectException(ForbiddenException::class);

        $token = $this->virtualProjectService->changePushToken(
            $this->createMock(User::class),
            1
        );
    }

    public function testSuccessProjectInviteTokenChanging(): void
    {
        $user = $this->createMock(User::class);
        $project = $this->createMock(VirtualProject::class);
        $project->method('getOwner')->willReturn($user);
        $this->virtualProjectRepository->method('getById')->willReturn($project);
        $this->tokenGenerator->method('generate')->willReturn('token');
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $token = $this->virtualProjectService->changeInviteToken(
            $user,
            1
        );

        $this->assertNotEmpty($token);
    }

    public function testPermissionDeniedOnInviteTokenChanging(): void
    {
        $this->virtualProjectService = new VirtualProjectService(
            $this->virtualProjectRepository,
            $this->tokenGenerator,
            $this->userRepository
        );

        $this->expectException(ForbiddenException::class);

        $token = $this->virtualProjectService->changeInviteToken(
            $this->createMock(User::class),
            1
        );
    }
}
