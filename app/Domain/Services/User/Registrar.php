<?php

declare(strict_types=1);

namespace App\Domain\Services\User;

use App\Domain\Entities\User;
use App\Domain\Exceptions\User\UserAlreadyExistsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\User\Interfaces\PasswordHasher;
use App\Domain\ValueObjects\User\CleanPassword;
use App\Domain\ValueObjects\User\Email;
use App\Domain\ValueObjects\User\Name;

final class Registrar
{
    private UserRepository $userRepository;

    private PasswordHasher $passwordHasher;

    public function __construct(UserRepository $userRepository, PasswordHasher $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(Name $name, Email $email, CleanPassword $cleanPassword): User
    {
        if ($this->userRepository->hasByEmail($email)) {
            throw new UserAlreadyExistsException();
        }

        $user = new User($name, $email, $this->passwordHasher->hash($cleanPassword));
        $this->userRepository->persist($user);
        $this->userRepository->flush();

        return $user;
    }
}
