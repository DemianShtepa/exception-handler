<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Entities\VirtualProject;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\Repositories\VirtualProjectRepository;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\VirtualProject\Name as ProjectName;
use App\Domain\ValueObjects\User\Name as UserName;
use App\Infrastructure\Services\User\PasswordHasher;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;
    private VirtualProjectRepository $virtualProjectRepository;
    private User $user;

    public function run(
        UserRepository $userRepository,
        PasswordHasher $passwordHasher,
        ResetPasswordRequestRepository $resetPasswordRequestRepository,
        VirtualProjectRepository $virtualProjectRepository
    ): void
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
        $this->virtualProjectRepository = $virtualProjectRepository;

        $this->createUser();
        $this->createUserWithPasswordRequest();
        $this->createVirtualProject();
    }

    private function createUserWithPasswordRequest(): void
    {
        $this->user = new User(
            new UserName('Exist'),
            new Email('exist@exist.com'),
            $this->passwordHasher->hash(new CleanPassword('password')),
            new ApiToken('token', (new DateTimeImmutable())->add(new DateInterval('PT1H')))
        );

        $this->userRepository->save($this->user);
        $this->createResetPasswordRequest($this->user);
    }

    private function createResetPasswordRequest(User $user): void
    {
        $request = new ResetPasswordRequest($user, 'token', (new DateTimeImmutable())->add(new DateInterval('PT1H')));

        $this->resetPasswordRequestRepository->save($request);
    }

    private function createUser(): void
    {
        $user = new User(
            new UserName('Exist'),
            new Email('exist@exist.com1'),
            $this->passwordHasher->hash(new CleanPassword('password')),
            new ApiToken('token1', (new DateTimeImmutable())->add(new DateInterval('PT1H')))
        );

        $this->userRepository->save($user);
    }

    private function createVirtualProject(): void
    {
        $project = new VirtualProject(
            new ProjectName('name'),
            'token',
            'token',
            new DateTimeImmutable(),
            $this->user
        );

        $this->virtualProjectRepository->save($project);
    }
}
