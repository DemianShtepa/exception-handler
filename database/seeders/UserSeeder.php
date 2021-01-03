<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Entities\ApiToken;
use App\Domain\Entities\User;
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
    public function run(UserRepository $userRepository, PasswordHasher $passwordHasher): void
    {
        $user = new User(
            new Name('Exist'),
            new Email('exist@exist.com'),
            $passwordHasher->hash(new CleanPassword('password')),
            new ApiToken('token', (new DateTimeImmutable())->add(new DateInterval('PT1H')))
        );

        $userRepository->persist($user);
        $userRepository->flush();
    }
}
