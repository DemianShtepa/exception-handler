<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\ResetPasswordRequest;
use App\Domain\Entities\User;
use App\Domain\Repositories\ResetPasswordRequestRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;
use App\Infrastructure\Services\User\PasswordHasher;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private ResetPasswordRequestRepository $resetPasswordRequestRepository;

    public function run(
        UserRepository $userRepository,
        PasswordHasher $passwordHasher,
        ResetPasswordRequestRepository $resetPasswordRequestRepository
    ): void
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;

        $this->createUser();
        $this->createUserWithPasswordRequest();

        $this->userRepository->flush();
        $this->resetPasswordRequestRepository->flush();
    }

    private function createUserWithPasswordRequest(): void
    {
        $user = new User(
            new Name('Exist'),
            new Email('exist@exist.com'),
            $this->passwordHasher->hash(new CleanPassword('password')),
            new ApiToken('token', (new DateTimeImmutable())->add(new DateInterval('PT1H')))
        );

        $this->userRepository->persist($user);
        $this->createResetPasswordRequest($user);
    }

    private function createResetPasswordRequest(User $user): void
    {
        $request = new ResetPasswordRequest($user, 'token', (new DateTimeImmutable())->add(new DateInterval('PT1H')));

        $this->resetPasswordRequestRepository->persist($request);
    }

    private function createUser(): void
    {
        $user = new User(
            new Name('Exist'),
            new Email('exist@exist.com1'),
            $this->passwordHasher->hash(new CleanPassword('password')),
            new ApiToken('token1', (new DateTimeImmutable())->add(new DateInterval('PT1H')))
        );

        $this->userRepository->persist($user);
    }
}
