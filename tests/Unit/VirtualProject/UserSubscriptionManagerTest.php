<?php

declare(strict_types=1);

namespace Tests\Unit\VirtualProject;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Exceptions\User\UserAlreadySubscribedException;
use App\Domain\Exceptions\User\UserNotSubscribedException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\Services\VirtualProject\UserSubscriptionManager;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use Tests\TestCase;

class UserSubscriptionManagerTest extends TestCase
{
    private UserRepository $userRepository;
    private VirtualProjectRepository $virtualProjectRepository;
    private UserSubscriptionManager $userSubscriptionManager;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->virtualProjectRepository = $this->createMock(VirtualProjectRepository::class);
    }

    public function testSuccessfulSubscribing(): void
    {
        $user = new User(
            new Name('name'),
            new Email('test@mail.com'),
            'password',
            $this->createMock(ApiToken::class)
        );
        $project = $this->createMock(VirtualProject::class);
        $this->virtualProjectRepository
            ->method('getByInviteToken')
            ->willReturn($project);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(false);

        $this->userSubscriptionManager = new UserSubscriptionManager($this->userRepository, $this->virtualProjectRepository);

        $returnedUser = $this->userSubscriptionManager->subscribe($user, 'inviteToken');

        $this->assertContains($project, $returnedUser->getSubscriptions());
    }

    public function testUserAlreadySubscribed(): void
    {
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->userSubscriptionManager = new UserSubscriptionManager($this->userRepository, $this->virtualProjectRepository);

        $this->expectException(UserAlreadySubscribedException::class);

        $this->userSubscriptionManager->subscribe($this->createMock(User::class), 'inviteToken');
    }

    public function testSuccessfulUnsubscribing(): void
    {
        $user = new User(
            new Name('name'),
            new Email('test@mail.com'),
            'password',
            $this->createMock(ApiToken::class)
        );
        $project = $this->createMock(VirtualProject::class);
        $this->virtualProjectRepository
            ->method('getById')
            ->willReturn($project);
        $this->userRepository->method('isUserSubscribedTo')->willReturn(true);

        $this->userSubscriptionManager = new UserSubscriptionManager($this->userRepository, $this->virtualProjectRepository);

        $returnedUser = $this->userSubscriptionManager->unsubscribe($user, 1);

        $this->assertNotContains($project, $returnedUser->getSubscriptions());
    }

    public function testUserNotSubscribed(): void
    {
        $this->userRepository->method('isUserSubscribedTo')->willReturn(false);

        $this->userSubscriptionManager = new UserSubscriptionManager($this->userRepository, $this->virtualProjectRepository);

        $this->expectException(UserNotSubscribedException::class);

        $this->userSubscriptionManager->unsubscribe($this->createMock(User::class), 1);
    }
}
