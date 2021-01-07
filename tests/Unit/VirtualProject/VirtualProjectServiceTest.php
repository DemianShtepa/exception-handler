<?php

declare(strict_types=1);

namespace Tests\Unit\VirtualProject;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\User\UserAlreadySubscribedException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\Token\Interfaces\TokenGenerator;
use App\Domain\Services\VirtualProject\VirtualProjectService;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name as UserName;
use App\Domain\ValueObjects\VirtualProject\Name;
use DateTimeImmutable;
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
          new Name('somename'),
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
}
